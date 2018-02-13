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
            'icon' => '~/img/dashboard.svg',
            'name' => 'Dashboard',
            'url' => 'dashboard',
            'active' => 'dashboard(/*)?',
            'order' => 1
        ],
        'test1' => [
            'name' => 'Test1',
            'url' => 'dashboard/1',
            'parent' => 'dashboard'
        ],
        'test2' => [
            'name' => 'Test2',
            'url' => 'dashboard/2',
            'parent' => 'dashboard'
        ],
        'dashboard2' => [
            'icon' => '~/img/dashboard.svg',
            'name' => 'Dashboard',
            'url' => 'dashboard/test',
            'order' => 2
        ]
    ]

];

?>
