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

    'action' => [
        'application.boot' => function($app) {
            if(!$app->route->controller) {
                $app->route->redirect('dashboard');
            }
        }
    ],

    'menu' => [
        'dashboard' => [
            'icon' => 'chartIcon',
            'name' => 'Dashboard',
            'url' => 'dashboard',
            'order' => 0
        ]
    ]

];

?>
