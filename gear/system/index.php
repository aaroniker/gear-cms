<?php

return [

    'name' => 'system',

    'main' => function($app) {

    },

    'register' => [
        'modules/*/index.php'
    ],

    'routes' => [
        '/' => [
            'include' => 'dashboard'
        ],
        '/test' => [
            'include' => 'test'
        ]
    ],

    'required' => [
        'route'
    ],

    'hooks' => [
    ],

    'autoload' => [
    ]

];

?>
