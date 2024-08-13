<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OrderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Order',
        'description' => 'A Order',
        'model' => \App\Models\Orders::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the Order',
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
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The date the Order was created',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The date the Order was last updated',
                'resolve' => function ($model) {
                    return $model->updated_at;
                },
            ],
        ];
    }
}
