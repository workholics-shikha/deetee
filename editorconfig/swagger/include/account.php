<?php

return [
    'paths' => [
        '/operator-login' => [
            'post' => [
                'tags' => [
                    'account',
                ],
                'summary' => 'login',
                'description' => 'User login',
                'consumes' => [
                    'application/json',
                ],
                'produces' => [
                    'application/json',
                ],
                'parameters' => [
                    [
                        'in' => 'body',
                        'name' => 'body',
                        'description' => 'Login credentials',
                        'required' => true,
                        'schema' => [
                            '$ref' => '#/definitions/login',
                        ],
                    ],
                ],
                'responses' => [
                    'default' => [
                        'description' => 'successful operation',
                    ],
                ],
            ],
        ],
        '/scan-machine/{machine_id}' => [
            'get' => [
                'tags' => [
                    'scan-related',
                ],
                'summary' => 'Get machine details',
                'description' => 'Fetch details of a specific machine using machine ID',
                'produces' => [
                    'application/json',
                ],
                'parameters' => [
                    [
                        'name' => 'machine_id',
                        'in' => 'path',
                        'description' => 'ID of the machine to fetch details for',
                        'required' => true,
                        'type' => 'string',
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Machine details fetched successfully',
                    ],
                    '404' => [
                        'description' => 'Machine not found',
                    ],
                ],
            ],
        ],
        '/scan-sales-order/{so_id}' => [
            'get' => [
                'tags' => [
                    'scan-related',
                ],
                'summary' => 'Get sales-order details',
                'description' => 'Fetch details of a specific sales-order using sales-order ID',
                'produces' => [
                    'application/json',
                ],
                'parameters' => [
                    [
                        'name' => 'so_id',
                        'in' => 'path',
                        'description' => 'ID of the sales-order to fetch details for',
                        'required' => true,
                        'type' => 'string',
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Sales-order details fetched successfully',
                    ],
                    '404' => [
                        'description' => 'Sales-order not found',
                    ],
                ],
            ],
        ],

        '/scan-product/{p_id}' => [
            'get' => [
                'tags' => [
                    'scan-related',
                ],
                'summary' => 'Get product details',
                'description' => 'Fetch details of a specific product related to sales-order using sales-order > product ID',
                'produces' => [
                    'application/json',
                ],
                'parameters' => [
                    [
                        'name' => 'p_id',
                        'in' => 'path',
                        'description' => 'ID of the product of sales-order to fetch details',
                        'required' => true,
                        'type' => 'string',
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Product details fetched successfully',
                    ],
                    '404' => [
                        'description' => 'Product not found',
                    ],
                ],
            ],
        ],
    ],
    'definitions' => [
        'login' => [
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                ],
                // "password" => [
                //     "type" => "string"
                // ],
            ],
        ],
    ],
];
