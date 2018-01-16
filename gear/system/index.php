<?php

return [

    'name' => 'system',

    'main' => function($app) {
        echo 'mainfunc';
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
    ],

    'hooks' => [
    ]

];

?>
