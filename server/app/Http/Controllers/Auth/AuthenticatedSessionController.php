<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth_login;
use App\Models\Blocked_list;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if ($user === null) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->active != 'Activated') {
            return response()->json([
                'message' => 'Users account is deactivated.',
                'response' => $user->active,
            ], 422);
        }

        $data = json_decode(file_get_contents('https://ipinfo.io/json'), true);

        $blocked_ip = Blocked_list::where([
            'user_id' => $user->id,
            'ip' => $data['ip'],
        ])->first();

        if ($blocked_ip) {
            return response()->json([
                'message' => 'IP Address is Blocked.',
                'response' => $blocked_ip,
            ], 422);
        }

        $success = Auth::attempt($request->only('email', 'password')) ? 1 : 0;

        Auth_login::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => $data['ip'] ?? null,
            'country' => $data['country'] ?? null,
            'region' => $data['region'] ?? null,
            'user_agent' => $request->header('User-Agent'),
            'identifier' => $user ? $user->name : null,
            'success' => $success,
        ]);

        if ($success === 0) {
            return response()->json([
                'message' => 'These credentials do not match our records.',
            ], 422);
        }

        $user->tokens()->delete();
        $token = $user->createToken('access-token');

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
