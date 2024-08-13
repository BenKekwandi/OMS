<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceCompanyRequest;
use App\Http\Resources\InvoiceCompanyResource;
use App\Models\Invoice_company;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class InvoiceCompanyController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Display all Invoice Copnaies.
     */
    public function index(): AnonymousResourceCollection
    {
        return InvoiceCompanyResource::collection(Invoice_company::all());
    }


    public function store(InvoiceCompanyRequest $request)
    {
        $invoice_company = new InvoiceCompanyResource(Invoice_company::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $invoice_company,
        ], 201);

    }

    public function update(InvoiceCompanyRequest $request, Invoice_company $invoice_company)
    {
        $invoice_company->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Invoice Company updated successfully.',
            'data' => new InvoiceCompanyResource($invoice_company),
        ]);
    }

    public function show(Invoice_company $invoice_company)
    {
        return new InvoiceCompanyResource($invoice_company);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Invoice_company');
    }
}
