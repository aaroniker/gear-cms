<?php

return [

    'name' => 'auth',

    'run' => function($app) {
        $app->auth = new auth($app, $this);
    },

    'autoload' => [
        'classes'
    ],

    'config' => [
        'table' => 'users',
        'session' => [
            'table' => 'users_session',
            'key' => 'jdUkd8s2!7HVHG7777ghg',
            'cookieName' => 'login',
            'time' => '+30 minutes',
            'remember' => '+1 month'
        ],
        'attempts' => [
            'table' => 'users_attempt',
            'count' => 5,
            'ban' => '+5 minutes'
        ]
    ]

];

?>
