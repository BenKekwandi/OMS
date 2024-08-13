<?php

namespace App\Http\Controllers;

use App\Services\InternalUsers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LogisticController extends Controller
{
    protected $internalUsers;

    public function __construct(InternalUsers $internalUsers)
    {
        $this->internalUsers = $internalUsers;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->internalUsers->allUsers('logistic');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->internalUsers->createUser($request, 'logistic');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->internalUsers->user($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->internalUsers->updateUser($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        return $this->internalUsers->userDeactivate($request);
    }

    public function reactivate(Request $request)
    {
        return $this->internalUsers->userReactivate($request);
    }

    // public function export()
    // {
    //     return $this->internalUsers->export('logistic');
    // }
}
