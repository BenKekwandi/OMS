<?php

namespace App\Http\Controllers;

use App\Services\InternalUsers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PMController extends Controller
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
        return $this->internalUsers->allUsers('pm');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->internalUsers->createUser($request, 'pm');

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

    public function supplierByPm($id)
    {
        return $this->internalUsers->modelByUser($id, 'Supplier');
    }

    public function export()
    {
        return $this->internalUsers->exports('pm');
    }

    public function import(Request $request)
    {
        return $this->internalUsers->imports($request, 'pm');
    }
}
