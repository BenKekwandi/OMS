<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth_login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//registration with 2fa
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Events\RecoveryCodesGenerated;
use Laravel\Fortify\Events\TwoFactorAuthenticationConfirmed;
use Laravel\Fortify\Events\TwoFactorAuthenticationDisabled;
//login with 2fa
use Laravel\Fortify\Events\TwoFactorAuthenticationEnabled;
//Disable 2fa

use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
//Generate New Recovery Codes
use Laravel\Fortify\RecoveryCode;

class TwoFactorAuthController extends Controller
{
    protected $provider;

    public function __construct(TwoFactorAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'phone' => 'required|max:16',
            'address' => 'required|max:255',
            'country' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $user = User::create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'country' => $request->input('country'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            if (Features::enabled(Features::twoFactorAuthentication())) {

                if (empty($user->two_factor_secret)) {
                    $user->forceFill([
                        'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
                        'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                            return RecoveryCode::generate();
                        })->all())),
                    ])->save();

                    TwoFactorAuthenticationEnabled::dispatch($user);
                }

            }

            $response = [
                'user' => $user,
            ];

            return response($response, 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to Add a new User', 'error' => $e->getMessage()], 500);
        }
    }

    public function prelogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        $tokenString = $user->id.'_'.Carbon::now()->addHours(2)->timestamp;

        $token = encrypt($tokenString);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'two_factor_authentication_token' => $token,
            'message' => 'Correct Credentials',
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'code' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password']);

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        $code = $request->input('code');

        if (Features::enabled(Features::twoFactorAuthentication())) {
            if (empty($user->two_factor_secret) || empty($code) || ! $this->provider->verify(decrypt($user->two_factor_secret), $code)) {

                return response()->json([
                    'code' => $code,
                    'message' => 'The provided two factor authentication code was invalid.',
                ], 422);
            }

            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            TwoFactorAuthenticationConfirmed::dispatch($user);
        }

        $success = Auth::attempt($credentials);

        if (! $success) {
            return response()->json([
                'message' => 'These credentials do not match our records.',
            ], 401);
        }

        $data = json_decode(file_get_contents('https://ipinfo.io/json'), true);

        $loginData = [
            'user_id' => $user ? $user->id : null,
            'ip_address' => $data['ip'] ?? null,
            'country' => $data['country'] ?? null,
            'region' => $data['region'] ?? null,
            'user_agent' => $request->header('User-Agent'),
            'identifier' => $user ? $user->name : null,
            'success' => $success,
        ];

        Auth_login::create($loginData);

        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login Successfull',
        ], 200);
    }

    public function disableTwoFactorAuthentication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! is_null($user->two_factor_secret) || ! is_null($user->two_factor_recovery_codes) || ! is_null($user->two_factor_confirmed_at)) {
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ] + (Fortify::confirmsTwoFactorAuthentication() ? [
                'two_factor_confirmed_at' => null,
            ] : []))->save();

            TwoFactorAuthenticationDisabled::dispatch($user);
        }
    }

    public function enableTwoFactorAuthentication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        $user->forceFill([
            'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();

        TwoFactorAuthenticationEnabled::dispatch($user);

        return response()->json(
            [
                'status' => 'Success',
                'message' => 'Two Factor secret key and recovery codes successfully updated',
            ],
            200
        );

    }

    public function generateNewRecoveryCodes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();

        RecoveryCodesGenerated::dispatch($user);
    }

    public function getQrCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (! ($user && Hash::check($request->input('password'), $user->password))) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (empty($user->two_factor_secret)) {

            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'Two Factor Authentication disabled, You need to enable it first',
                ],
                422
            );
        }

        return response()->json([
            'svg' => $user->twoFactorQrCodeSvg(),
            'url' => $user->twoFactorQrCodeUrl(),
        ], 200);
    }

    public function get2faQrCode(Request $request)
    {

        $token = $request->input('token');

        try {

            $info = decrypt($token);

            $userId = intval(explode('-', $info)[0]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or corrupted token',
            ], 400);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (empty($user->two_factor_secret)) {

            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'Two Factor Authentication disabled, You need to enable it first',
                ],
                422
            );
        }

        return response()->json([
            'svg' => $user->twoFactorQrCodeSvg(),
            'url' => $user->twoFactorQrCodeUrl(),
        ], 200);
    }

    public function challenge(Request $request)
    {
        $token = $request->input('token');

        try {

            $info = decrypt($token);

            $userId = intval(explode('-', $info)[0]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or corrupted token',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|integer',
        ]);

        $code = $request->input('code');

        $user = User::find($userId);

        if (! $user) {
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => 'These credentials do not match our records.',
                ],
                422
            );
        }

        if (Features::enabled(Features::twoFactorAuthentication())) {
            if (empty($user->two_factor_secret) || empty($code) || ! $this->provider->verify(decrypt($user->two_factor_secret), $code)) {

                return response()->json([
                    'code' => $code,
                    'message' => 'The provided two factor authentication code was invalid.',
                ], 422);
            }

            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            if (! $request->user()) {
                if (! Auth::loginUsingId($userId)) {

                    return response()->json(['error' => 'User authentication failed.'], 401);
                }

                $user->tokens()->delete();
                $token = $user->createToken('access-token');

                return response()->json([
                    'status' => 'success',
                    'token' => $token->plainTextToken,
                    'user' => $user,
                    'message' => 'User successfully logged in',
                ], 200);

                TwoFactorAuthenticationConfirmed::dispatch($user);
            }

            return response()->json([
                'status' => 'success',
                'code' => $code,
                'message' => 'Successfull verification',
            ], 200);

            TwoFactorAuthenticationConfirmed::dispatch($user);
        }
    }
}
