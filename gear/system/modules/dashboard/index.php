<?php

return [

    'name' => 'system/dashboard',

    'run' => function($app) {
    },

    'routes' => [
        '/dashboard' => [
            'controller' => 'controller/dashboard'
        ]
    ],

    'action' => [
        'application.boot' => function($app) {
            if(!$app->route->route || $app->route->route == '/') {
                $app->route->redirect('/dashboard');
            }
        }
    ],

    'menu' => [
        'dashboard' => [
            'icon' => 'chartIcon',
            'name' => 'Dashboard',
            'url' => '/dashboard',
            'order' => 0
        ]
    ]

];

?>
