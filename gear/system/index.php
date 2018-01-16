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
            'include' => 'controller/dashboard'
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
