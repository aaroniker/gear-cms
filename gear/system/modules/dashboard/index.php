<?php

return [

    'name' => 'system/dashboard',

    'run' => function($app) {

    },

    'routes' => [
        '/dashboard' => [
            'name' => 'dashboard',
            'controller' => 'controller/dashboard'
        ]
    ],

    'filter' => [
        'route.controller.setURL' => function($app, $controller) {
            if(!$controller) {
                return 'dashboard';
            }
            return $controller;
        }
    ]

];

?>
