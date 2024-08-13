<?php

namespace App\GraphQL\Queries;

use App\Models\Proposal;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ProposalQuery extends Query
{
    protected $attributes = [
        'name' => 'Proposals',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('ProposalType'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],

        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Proposal::with('brand', 'customer')->where('id', $args['id'])->get();
        }

        return Proposal::all();
    }
}
