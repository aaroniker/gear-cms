<?php

return [

    'name' => 'system',

    'run' => function($app) {

    },

    'register' => [
        'modules/*/index.php'
    ],

    'required' => [
        'route',
        'auth',
        'database'
    ]

];

?>
