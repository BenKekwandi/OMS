<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CustomerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Customer',
        'description' => 'A Customer',
        'model' => \App\Models\Customer::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the Customer',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the Customer',
            ],
            'surname' => [
                'type' => Type::string(),
                'description' => 'The surname of the Customer',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the Customer',
            ],
            'country_id' => [
                'type' => Type::int(),
                'description' => 'The country of the Customer',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The date the Customer was created',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The date the Customer was last updated',
                'resolve' => function ($model) {
                    return $model->updated_at;
                },
            ],
        ];
    }
}
