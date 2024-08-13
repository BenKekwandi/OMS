<?php

namespace App\GraphQL\Queries;

use App\Models\Orders;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class OrderQuery extends Query
{
    protected $attributes = [
        'name' => 'Orders',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('OrderType'));
    }

    public function args(): array
    {
        return [

            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'offer_id' => [
                'name' => 'offer_id',
                'type' => Type::int(),
            ],
            'brand_id' => [
                'name' => 'brand_id',
                'type' => Type::int(),
            ],
            'customer_id' => [
                'name' => 'customer_id',
                'type' => Type::int(),
            ],
            'customer' => [
                'name' => 'customer',
                'type' => GraphQL::type('CustomerType'),
            ],
            'brand' => [
                'name' => 'brand',
                'type' => GraphQL::type('BrandType'),
            ],
            'supplier_id' => [
                'name' => 'supplier_id',
                'type' => Type::int(),
            ],
            'shipment_id' => [
                'name' => 'shipment_id',
                'type' => Type::int(),
            ],
            'photo' => [
                'name' => 'photo',
                'type' => Type::string(),
            ],
            'other_features' => [
                'name' => 'other_features',
                'type' => Type::string(),
            ],
            'reference_number' => [
                'name' => 'reference_number',
                'type' => Type::string(),
            ],
            'expected_arrival' => [
                'name' => 'expected_arrival',
                'type' => Type::string(),
            ],
            'actual_arrival' => [
                'name' => 'actual_arrival',
                'type' => Type::string(),
            ],
            'shipment_date' => [
                'name' => 'shipment_date',
                'type' => Type::string(),
            ],
            'expected_delivery_at' => [
                'name' => 'expected_delivery_at',
                'type' => Type::string(),
            ],
            'finalized_at' => [
                'name' => 'finalized_at',
                'type' => Type::string(),
            ],
            'deadline' => [
                'name' => 'deadline',
                'type' => Type::string(),
            ],
            'confirmed_at' => [
                'name' => 'confirmed_at',
                'type' => Type::string(),
            ],
            'status' => [
                'name' => 'status',
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Orders::query();

        foreach ($args as $key => $value) {
            $query->where($key, $value);
            // switch($key){

            //     case 'brand_id':
            //         $query->with('brand')->where($key,$value);
            //         break;
            //     case 'customer_id':
            //         $query->with('customer')->where($key, $value);
            //         break;
            //     case 'customer':
            //         $query->with('customer')->where($key, $value);
            //         break;
            //     case 'supplier_id':
            //         $query->with('supplier')->where($key, $value);
            //         break;
            //     default:
            //         $query->with('brand','customer','supplier')->where($key, $value);
            //         break;
            // }
        }

        return $query->get();
    }
}
