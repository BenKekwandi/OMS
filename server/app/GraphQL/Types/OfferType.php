<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OfferType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Offer',
        'description' => 'A Offer',
        'model' => \App\Models\Offers::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the Offer',
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
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The date the Offer was created',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The date the Offer was last updated',
                'resolve' => function ($model) {
                    return $model->updated_at;
                },
            ],
        ];
    }
}
