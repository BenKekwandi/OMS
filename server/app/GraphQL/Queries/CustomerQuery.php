<?php

namespace App\GraphQL\Queries;

use App\Models\Customer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class CustomerQuery extends Query
{
    protected $attributes = [
        'name' => 'Customers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('CustomerType'));
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
            return Customer::with('country')->where('id', $args['id'])->get();
        }

        if (isset($args['name'])) {
            return Customer::with('country')->where('name', $args['name'])->get();
        }

        if (isset($args['surname'])) {
            return Customer::with('country')->where('surname', $args['surname'])->get();
        }

        if (isset($args['email'])) {
            return Customer::with('country')->where('email', $args['email'])->get();
        }

        if (isset($args['country_id'])) {
            return Customer::with('country')->where('country_id', $args['country_id'])->get();
        }

        return Customer::with('country')->get();
    }
}
