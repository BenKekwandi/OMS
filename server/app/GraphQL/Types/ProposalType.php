<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProposalType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Proposal',
        'description' => 'A Proposal',
        'model' => \App\Models\Proposal::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the Proposal',
            ],

            'created_at' => [
                'type' => Type::string(),
                'description' => 'The date the Proposal was created',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The date the Proposal was last updated',
                'resolve' => function ($model) {
                    return $model->updated_at;
                },
            ],
        ];
    }
}
