<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class helpers
{
    public function deactivate(Request $request, array $allowedRoles, string $modelName): JsonResponse
    {

        $user = Auth::user();

        if (!$this->isAuthorized($user, $allowedRoles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $recordIds = $request->all();

        if (empty($recordIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No record ID provided.',
            ], 400);
        }
        $modelClass = app()->make('App\\Models\\' . $modelName);

        foreach ($recordIds as $recordId) {
            $id = $recordId['id'];
            $record = $modelClass::find($id);
            if ($record) {
                $record->delete();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ], 201);
    }

    public function reactivate(Request $request, array $allowedRoles, string $modelName): JsonResponse
    {

        $user = Auth::user();

        if (!$this->isAuthorized($user, $allowedRoles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        log::info($request->all());

        $recordIds = $request->items;

        if (empty($recordIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No record ID provided.',
            ], 400);
        }
        $modelClass = app()->make('App\\Models\\' . $modelName);

        foreach ($recordIds as $recordId) {
            $record = $modelClass::find($recordId);
            if ($record) {
                if ($request->deadline) {
                    // $record->deadline = Carbon::now()->addWeeks(2);
                    $record->update([
                        'deadline' => $request->deadline,
                        'status' => 1,
                    ]);
                } else {
                    $record->update([
                        'created_at' => now(),
                        'status' => 1,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record renewed successfully.',
        ], 201);
    }

    private function isAuthorized($user, $allowedRoles)
    {
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function transfer(Request $request, $id, $modelName)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        $modelClass = app()->make('App\\Models\\' . $modelName);

        $record = $modelClass::find($id);

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $record->update([
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record Transferred Successfully',
            'record' => $record,
        ], 201);
    }
}
