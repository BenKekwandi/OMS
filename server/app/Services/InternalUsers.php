<?php

namespace App\Services;

use App\Exports\UsersExport;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\JsonResponse;

class InternalUsers
{
    public function allUsers($roleName)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $users = User::role($roleName)->where('active', '!=', 0)->get();

        return response()->json([
            'status' => 'success',
            $roleName . 's' => $users,
        ]);
    }

    public function users()
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $usersWithRoles = User::where('active', '!=', 0)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->with('roles')
            ->get();

        // Transform the data to include roles
        $users = $usersWithRoles->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'country' => $user->country,
                'phone' => $user->phone,
                'status' => $user->active,
                'created_at' => $user->created_at,
                'role' => $user->roles->pluck('name')->first(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function createUser(Request $request, $roleName): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'surname' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $request->phone,
            'password' => Hash::make('147258369'),
        ]);
        $user->createToken('access-token');

        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($roleName);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $user,
        ], 201);

    }

    public function userCreate(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'surname' => 'required|string',
            'country' => 'required|string',
            'phone' => 'nullable|string',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $request->phone,
            'password' => Hash::make('147258369'),
        ]);
        $user->createToken('access-token');

        $role = Role::where('name', $request->role)->first();

        if ($role) {
            $user->assignRole($request->role);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $user,
        ], 201);

    }

    public function user(string $id)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function updateUser(Request $request, string $id)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'surname' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
        ]);
        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $user,
        ]);
    }

    public function userDeactivate(Request $request)
    {

        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $userIds = $request->all();
        log::info($request->all());

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
                $user->update(['active' => 0]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record deactivated successfully.',
        ]);
    }

    public function userReactivate(Request $request)
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
            'message' => 'Record deactivated successfully.',
        ]);
    }

    public function modelByUser($id, $modelName)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $modelClass = app()->make('App\\Models\\' . $modelName);
        if ($modelName === 'Supplier') {
            $record = $modelClass::where('user_id', $id)->with('country', 'brands:id,name')->get();
        } else
            $record = $modelClass::where('user_id', $id)->with('country')->get();


        return response()->json([
            'status' => 'success',
            'record' => $record,
        ]);
    }

    public function exports($roleName)
    {
        return Excel::download(new UsersExport($roleName), $roleName . 's.csv');
    }

    public function imports(Request $request, $roleName)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        $file = $request->file('file');

        return Excel::import(new UserImport($roleName), $file);

    }
}
