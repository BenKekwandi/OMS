<?php

namespace App\Http\Controllers;

use App\Models\Auth_login;
use App\Models\Blocked_list;
use App\Models\User;
use App\Services\InternalUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    protected $internalUsers;

    public function __construct(InternalUsers $internalUsers)
    {
        $this->internalUsers = $internalUsers;
    }

    public function index()
    {
        return $this->internalUsers->users();

    }

    public function store(Request $request): JsonResponse
    {
        return $this->internalUsers->userCreate($request);

    }

    public function update(Request $request, string $id)
    {
        return $this->internalUsers->updateUser($request, $id);
    }

    public function export(Request $request)
    {
        return $this->internalUsers->exports($request->role);
    }

    public function userCreate(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:$users,email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User has already been taken',
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]);

            return response([
                'status' => true,
                'message' => 'User has been created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function userLogin(Request $request)
    {
        try {

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User has already been taken',
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password or Email is incorrect',
                ], 401);
            }

            $user = User::Where(['email', '=', $request->email])->first();
            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'User Exist',
                ], 200);
            }

            return response([
                'status' => true,
                'message' => 'User has been created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function UserImage(Request $request)
    {
        try {
            $user = Auth::user();
            log::info($request->all());
            $request->validate([
                'image' => ['required', 'image', 'max:2048'],
            ]);

            if ($request->hasFile('image')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('avatars', $imageName, 'public');
                $user->avatar = $path;
                $user->save();
            } else {
                throw new \Exception('Image not found in the request.');
            }

            return response()->json([
                'status' => true,
                'message' => 'User image updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function UserInfo(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $lastLogin = Auth_login::where('user_id', $user->id)
            ->latest('login_datetime')
            ->skip(1)
            ->first();

        $userDetails = [
            'id' => $user->id,
            'name' => $user->name . ' ' . $user->surname,
            'phone' => $user->phone,
            'email' => $user->email,
            'country' => $user->country,
            'address' => $user->address,
            'last_login' => $lastLogin ? $lastLogin->login_datetime : now()->format('Y-m-d H:i:s'),
        ];
        return $userDetails;
    }

    public function Dusers()
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $users = User::with('roles')->where('active', '!=', 1)->get();

        $users->transform(function ($user) {
            $user->role_name = $user->roles->pluck('name')->first();
            unset($user->roles);
            return $user;
        });

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function Userreactivate(Request $request)
    {

        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $userIds = $request->all();

        if (empty($userIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user ID provided.',
            ], 400);
        }

        foreach ($userIds as $userId) {
            $id = $userId['id'];
            $user = User::find($id);
            if ($user) {
                $user->update(['active' => 1]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record reactivated successfully.',
        ]);
    }

    public function userDreactivate(Request $request)
    {

        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $userIds = $request->all();

        if (empty($userIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user ID provided.',
            ], 400);
        }

        foreach ($userIds as $userId) {
            $id = $userId['id'];
            $user = User::find($id);
            if ($user) {
                $user->update(['active' => 0, 'deactived_at' => now()->format('Y-m-d H:i:s')]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record Deactivated successfully.',
        ]);
    }

    public function Usersauth()
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $usersAuth = Auth_login::all();
        foreach ($usersAuth as $userAuth) {
            $user_id = $userAuth->user_id;
            $ip_address = $userAuth->ip_address;

            $block_list = Blocked_list::where(['user_id' => $user_id, 'ip' => $ip_address])->exists();

            $userAuth->status = $block_list ? 0 : 1;
        }

        return response()->json([
            'status' => 'success',
            'data' => $usersAuth,
        ]);
    }

    public function Blockip(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $ip = $request->ip;
        $block_list = Blocked_list::where(['user_id' => $id, 'ip' => $ip])->first();

        if ($block_list) {
            return response()->json([
                'message' => 'User IP is Already Blocked',
            ]);
        }

        Blocked_list::create([
            'user_id' => $id,
            'ip' => $ip,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User IP is Blocked Successfully',
        ]);
    }

    public function Unblockip(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $list = Blocked_list::where(['user_id' => $id, 'ip' => $request->ip])->first();
        if (!$list) {
            return response()->json([
                'message' => 'User IP is Already Unblocked',
            ]);
        }
        $list->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'IP Unblocked Successfully',
        ]);
    }
}
