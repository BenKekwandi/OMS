<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\ExpensesResource;
use App\Models\Expenses;
use Log;

class ExpensesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpensesRequest $request)
    {
        // log::info($request->validated());
        $expenses = new ExpensesResource(Expenses::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $expenses,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpensesRequest $request, $id)
    {
        log::info($id);

        $expenses = Expenses::find($id);

        $expenses->update($request->validated());

        new ExpensesResource($expenses);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $expenses,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expenses = Expenses::find($id);
        $expenses->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
            'data' => $expenses,
        ], 201);
    }
}
