<?php

return [

    'name' => 'system',

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
    ]

];

?>
