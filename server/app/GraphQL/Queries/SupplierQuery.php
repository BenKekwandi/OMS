<?php

namespace App\GraphQL\Queries;

use App\Models\Supplier;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class SupplierQuery extends Query
{
    protected $attributes = [
        'name' => 'Suppliers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('SupplierType'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string(),
            ],
            'age' => [
                'name' => 'age',
                'type' => Type::int(),
            ],
            'country' => [
                'name' => 'country',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Supplier::where('id', $args['id'])->get();
        }

        if (isset($args['name'])) {
            return Supplier::where('name', $args['name'])->get();
        }

        if (isset($args['surname'])) {
            return Supplier::where('surname', $args['surname'])->get();
        }

        if (isset($args['email'])) {
            return Supplier::where('email', $args['email'])->get();
        }

        if (isset($args['country_id'])) {
            return Supplier::where('country_id', $args['country_id'])->get();
        }

        return Supplier::all();
    }
}
