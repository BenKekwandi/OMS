<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExpensesTypesController;
use App\Http\Controllers\InvoiceCompanyController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LabelInvoiceController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfficeAddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderShipmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PMController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ShipmentAccountController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentServiceController;
use App\Http\Controllers\SMController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Filters\OfferFilter;
use App\Http\Filters\OrderFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $role = $user->roles->first()->name;

        $userDetails = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'role' => $role,
        ];

        return $userDetails;
    });

    //PM Routes
    Route::get('/test', [TestController::class, 'index']);
    Route::get('/pms', [PMController::class, 'index']);
    Route::post('/pm-create', [PMController::class, 'store']);
    Route::get('/pm/{id}', [PMController::class, 'show']);
    Route::get('/pm-supplier/{id}', [PMController::class, 'supplierByPm']);
    Route::post('/pm-delete', [PMController::class, 'destroy']);
    Route::post('/pm-active', [PMController::class, 'reactivate']);
    Route::put('/pm/{id}', [PMController::class, 'update']);
    Route::get('/pms/export', [PMController::class, 'export']);
    Route::post('/pms/import', [PMController::class, 'import']);

    //SM Routes
    Route::get('/sms', [SMController::class, 'index']);
    Route::post('/sm-create', [SMController::class, 'store']);
    Route::get('/sm/{id}', [SMController::class, 'show']);
    Route::get('/sm-customer/{id}', [SMController::class, 'customerBySm']);
    Route::post('/sm-delete', [SMController::class, 'destroy']);
    Route::post('/sm-active', [SMController::class, 'reactivate']);
    Route::put('/sm/{id}', [SMController::class, 'update']);
    Route::get('/sms/export', [SMController::class, 'export']);

    //Accounting Routes
    Route::get('/accountings', [AccountingController::class, 'index']);
    Route::post('/accounting-create', [AccountingController::class, 'store']);
    Route::get('/accounting/{id}', [AccountingController::class, 'show']);
    Route::post('/accounting-delete', [AccountingController::class, 'destroy']);
    Route::post('/accounting-active', [AccountingController::class, 'reactivate']);
    Route::put('/accounting/{id}', [AccountingController::class, 'update']);
    Route::get('/accountings/export', [AccountingController::class, 'export']);

    //Logistic Routes
    Route::get('/logistics', [LogisticController::class, 'index']);
    Route::post('/logistic-create', [LogisticController::class, 'store']);
    Route::get('/logistic/{id}', [LogisticController::class, 'show']);
    Route::post('/logistic-delete', [LogisticController::class, 'destroy']);
    Route::put('/logistic/{id}', [LogisticController::class, 'update']);
    Route::get('/logistics/export', [CustomerController::class, 'export']);

    //Brand Routes
    Route::get('/brands', [BrandController::class, 'index']);
    Route::post('/brand-create', [BrandController::class, 'store']);
    Route::get('/brand/{id}', [BrandController::class, 'show']);
    Route::post('/brand-delete', [BrandController::class, 'destroy']);
    Route::put('/brand/{id}', [BrandController::class, 'update']);

    //Warehouse Routes
    Route::get('/warehouses', [WarehouseController::class, 'index']);
    Route::post('/warehouse-create', [WarehouseController::class, 'store']);
    Route::get('/warehouse/{id}', [WarehouseController::class, 'show']);
    Route::post('/warehouse-delete', [WarehouseController::class, 'destroy']);
    Route::put('/warehouse/{id}', [WarehouseController::class, 'update']);

    //Country Routes
    Route::get('/countries', [CountryController::class, 'index']);
    Route::post('/country-create', [CountryController::class, 'store']);
    Route::get('/country/{id}', [CountryController::class, 'show']);
    Route::post('/country-delete', [CountryController::class, 'destroy']);
    Route::put('/country/{id}', [CountryController::class, 'update']);

    //Invoice Companies Routes
    Route::get('/invoice-companies', [InvoiceCompanyController::class, 'index']);
    Route::post('/invoice-company-create', [InvoiceCompanyController::class, 'store']);
    Route::get('/invoice-company/{invoice_company}', [InvoiceCompanyController::class, 'show']);
    Route::post('/invoice-company-delete', [InvoiceCompanyController::class, 'destroy']);
    Route::put('/invoice-company/{invoice_company}', [InvoiceCompanyController::class, 'update']);

    //Expenses Types Routes
    Route::get('/expenses-types', [ExpensesTypesController::class, 'index']);
    Route::post('/expenses-type-create', [ExpensesTypesController::class, 'store']);
    Route::get('/expenses-type/{id}', [ExpensesTypesController::class, 'show']);
    Route::post('/expenses-type-delete', [ExpensesTypesController::class, 'destroy']);
    Route::put('/expenses-type/{id}', [ExpensesTypesController::class, 'update']);

    //Expenses Routes
    Route::apiResource('expense', ExpensesController::class);

    //Payment Routes
    Route::apiResource('payment', PaymentController::class);

    //Shipment Routes
    Route::apiResource('shipment', ShipmentController::class)->except(['destroy']);
    Route::post('/shipment-delete', [ShipmentController::class, 'destroy']);


    //Shipment Account Routes
    Route::apiResource('shipment-account', ShipmentAccountController::class)->except(['destroy']);
    Route::post('/shipment-account-delete', [ShipmentAccountController::class, 'destroy']);
    Route::get('/shipment-account-service/{id}', [ShipmentAccountController::class, 'accountByService']);

    //Shipment Service Routes
    Route::apiResource('shipment-service', ShipmentServiceController::class)->except(['destroy']);
    Route::post('/shipment-service-delete', [ShipmentServiceController::class, 'destroy']);

    //Order Shipment Routes
    Route::apiResource('order-shipment', OrderShipmentController::class);
    Route::get('/order-shipment-list/{id}', [OrderShipmentController::class, 'getOrders']);
    Route::post('/order-shipment-delete', [OrderShipmentController::class, 'deleteOrder']);

    //Office Address Routes
    Route::apiResource('office-address', OfficeAddressController::class)->except(['destroy']);
    Route::post('/office-address-delete', [OfficeAddressController::class, 'destroy']);

    //Label Routes
    Route::apiResource('label', LabelController::class);
    Route::put('label-stepback/{label}', [LabelController::class, 'stepBack']);

    //Label Routes
    Route::apiResource('label-invoice', LabelInvoiceController::class);

    Route::get('/api-test', [TestController::class, 'apiTest']);

    //model Routes
    Route::get('/models', [ModelController::class, 'index']);
    Route::post('/model-create', [ModelController::class, 'store']);
    Route::get('/model/{id}', [ModelController::class, 'show']);
    Route::get('/model-brand/{id}', [ModelController::class, 'modelByBrand']);
    Route::post('/model-delete', [ModelController::class, 'destroy']);
    Route::put('/model/{id}', [ModelController::class, 'update']);

    //Customer Routes
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customer-create', [CustomerController::class, 'store']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::post('/customer-delete', [CustomerController::class, 'destroy']);
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::get('/customers/export', [CustomerController::class, 'export']);
    Route::put('/customer-transfer/{id}', [CustomerController::class, 'transfer']);

    //Suppliers Routes
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/supplier-create', [SupplierController::class, 'store']);
    Route::get('/supplier/{id}', [SupplierController::class, 'show']);
    Route::post('/supplier-delete', [SupplierController::class, 'destroy']);
    Route::put('/supplier/{id}', [SupplierController::class, 'update']);
    Route::get('/suppliers/export', [SupplierController::class, 'export']);
    Route::put('/supplier-transfer/{id}', [SupplierController::class, 'transfer']);

    //Request Routes
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/request-create', [RequestController::class, 'store']);
    Route::get('/request/{id}', [RequestController::class, 'show']);
    Route::delete('/request/{id}', [RequestController::class, 'destroy']);
    Route::put('/request/{id}', [RequestController::class, 'update']);

    //Offer Routes
    Route::get('/offers', [OfferController::class, 'index']);
    Route::post('/offer-create', [OfferController::class, 'store']);
    Route::get('/offer/{id}', [OfferController::class, 'show']);
    Route::post('/offer-delete', [OfferController::class, 'destroy']);
    Route::put('/offer/{id}', [OfferController::class, 'update']);
    // Route::get('/offer-net-price', [OfferController::class, 'getNetprice']);
    Route::get('/offers/export', [OfferController::class, 'export']);
    Route::post('/offers/import', [OfferController::class, 'import']);
    Route::post('/offer-reset', [OfferController::class, 'reset']);
    Route::post('/offer-query', [OfferFilter::class, 'index']);

    //Order Routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/account-orders', [OrderController::class, 'accountingOrders']);
    Route::get('/logistic-orders', [OrderController::class, 'logisticOrders']);
    Route::post('/order-create', [OrderController::class, 'store']);
    Route::get('/order/{order}', [OrderController::class, 'show']);
    Route::post('/order-delete', [OrderController::class, 'destroy']);
    Route::put('/order/{id}', [OrderController::class, 'update']);
    Route::put('/order-confirm/{id}', [OrderController::class, 'confirm']);
    Route::put('/invoice-update/{id}', [OrderController::class, 'invoiceUpdate']);
    Route::get('/invoice-file/{id}', [OrderController::class, 'invoiceFile']);
    Route::get('/orders/export', [OrderController::class, 'export']);
    Route::post('/orders/import', [OrderController::class, 'import']);
    Route::post('/order-reset', [OrderController::class, 'reset']);
    Route::put('/customer-invoice/{id}', [OrderController::class, 'customerInvoice']);
    Route::post('/order-query', [OrderFilter::class, 'index']);
    Route::post('/acc-orders/export', [OrderController::class, 'accountingExport']);

    Route::put('/set-collected/{id}', [OrderController::class, 'setCollected']);
    Route::put('/set-delivered/{id}', [OrderController::class, 'setDelivered']);
    Route::put('/set-finalized/{id}', [OrderController::class, 'setfinalized']);

    //Proposal Routes
    Route::get('/proposals', [ProposalController::class, 'index']);
    Route::post('/proposal-create', [ProposalController::class, 'store']);
    Route::get('/proposal/{id}', [ProposalController::class, 'show']);
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy']);
    Route::put('/proposal-update/{id}', [ProposalController::class, 'update']);
    Route::put('/proposal-confirm/{id}', [ProposalController::class, 'confirm']);
    Route::put('/proposal-cancel/{id}', [ProposalController::class, 'cancel']);
    Route::put('/proposal-pcancel/{id}', [ProposalController::class, 'pmcancel']);
    Route::put('/proposal-pconfirm/{id}', [ProposalController::class, 'pmconfirm']);
    Route::get('/sm-confirmations', [ProposalController::class, 'smConfirmation']);
    Route::get('/pm-confirmations', [ProposalController::class, 'pmConfirmation']);

    //UserProfile Routes
    Route::post('/upload-image', [UserController::class, 'UserImage']);
    // Route::get('/user-info', [UserController::class, 'Userinfo']);
    Route::get('/d-users', [UserController::class, 'Dusers']);
    Route::post('/user-active', [UserController::class, 'Userreactivate']);
    Route::post('/user-deactivate', [UserController::class, 'userDreactivate']);
    Route::post('/block/{id}', [UserController::class, 'Blockip']);
    Route::post('/unblock/{id}', [UserController::class, 'Unblockip']);
    Route::get('/users-auth', [UserController::class, 'Usersauth']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users-create', [UserController::class, 'store']);
    Route::post('/users-export', [UserController::class, 'export']);
    Route::put('/users-update/{id}', [UserController::class, 'update']);

    //matching(Offers and Orders)
    Route::get('/matching-offers/{id}', [OrderController::class, 'matchingOffers']);
    // Route::get('/matching-orders/{id}', [OfferController::class, 'matchingOrders']);

    Route::post('/test-notification', [TestController::class, 'notification']);

});
