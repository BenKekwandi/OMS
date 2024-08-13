<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SupplierType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Supplier',
        'description' => 'A Supplier',
        'model' => \App\Models\Supplier::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the Supplier',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the Supplier',
            ],
            'surname' => [
                'type' => Type::string(),
                'description' => 'The surname of the Supplier',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the Supplier',
            ],
            'country_id' => [
                'type' => Type::int(),
                'description' => 'The country of the Supplier',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The date the Supplier was created',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The date the Supplier was last updated',
                'resolve' => function ($model) {
                    return $model->updated_at;
                },
            ],
        ];
    }
}
