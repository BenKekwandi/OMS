<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Notifications\TestNotification;
use App\Models\User;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return response()->json([
                'status' => true,
                'message' => 'You have logged in. This is your role: ' . $user->roles->first()->name . '.',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have the required role to access this endpoint.',
            ], 403);
        }
    }

    public function notification()
    {
        $user = User::where(['email' => 'benkekwandi@gmail.com'])->first();
        $user->notify(new TestNotification());
        return response()->json(['message' => 'Notification successfully sent'], 200);
    }

    public function apiTest()
    {
        $apiKey = '';
        $url = 'https://sandbox-api.aftership.com/postmen/v3/couriers';

        $options = [
            'http' => [
                'header' => "as-api-key: $apiKey\r\n"
            ]
        ];
        $curl = '{
            "service_type": "shipment_service.title",
            "shipper_account": {
                "id": "shipment_account.postmen_id"
            },
            "shipment": {
                "ship_from": {
                "contact_name": "office_address.contact_name",
                "company_name": "office_address.company_name",
                "street1": "office_address.street1",
                "city": "office_address.city",
                "state": "office_address.state",
                "postal_code": "office_address.postal_code",
                "country": "office_address.country",
                "phone": "office_address.phone",
                "email": "office_address.email"
                },
                "ship_to": {
                "contact_name": "office_address.contact_name",
                "company_name": "office_address.company_name",
                "street1": "office_address.street1",
                "city": "office_address.city",
                "state": "office_address.state",
                "postal_code": "office_address.postal_code",
                "country": "office_address.country",
                "phone": "office_address.phone",
                "email": "office_address.email"
                },
            }
            "parcels": [
            {
                "box_type": "custom",
                "dimension": {
                "width": shipment.width,
                "height": shipment.height,
                "depth": shipment.depth,
                "unit": "cm"
                },
                "items": [
                {
                    "description": "order.brand/order.reference_number",
                    "quantity": 1,
                    "price": {
                    "currency": "USD",
                    "amount": order.proposal.sell_price
                    },
                    "weight": {
                    "unit": "kg",
                    "value": shipment.weight
                    },
                    "sku": "order.reference_number",
                }
                ],
            }
            ]
        }'; 
        $context = stream_context_create($options);
        $data = file_get_contents($url, false, $context);

        if ($data === false) {
            // Handle error
            return 'Error fetching data';
        }

        $dataArray = json_decode($data, true);

        return $dataArray;

    }
}
