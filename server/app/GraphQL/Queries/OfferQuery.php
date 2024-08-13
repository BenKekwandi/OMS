<?php

namespace App\GraphQL\Queries;

use App\Models\Offers;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class OfferQuery extends Query
{
    protected $attributes = [
        'name' => 'Offers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('OfferType'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'order_days' => [
                'name' => 'order_days',
                'type' => Type::int(),
            ],
            'availability' => [
                'name' => 'availability',
                'type' => Type::int(),
            ],
            'brand' => [
                'name' => 'brand',
                'type' => GraphQL::type('BrandType'),
            ],
            'supplier_id' => [
                'name' => 'supplier_id',
                'type' => Type::int(),
            ],
            'supplier' => [
                'name' => 'supplier',
                'type' => GraphQL::type('SupplierType'),
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
            'serial_number' => [
                'name' => 'serial_number',
                'type' => Type::string(),
            ],
            'status' => [
                'name' => 'status',
                'type' => Type::int(),
            ],
            'with_image' => [
                'name' => 'with_image',
                'type' => Type::boolean(),
            ],
            'my_offers' => [
                'name' => 'my_offers',
                'type' => Type::boolean(),
            ],

        ];
    }

    public function resolve($root, $args)
    {
        $query = Offers::query();

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
