<?php

return [

    'name' => 'system/dashboard',

    'run' => function($app) {
        $app->assets->addJS('~/scripts/dist/test.js', 'vue');
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
            'order' => 1
        ],
        'test1' => [
            'name' => 'Test1',
            'url' => 'dashboard',
            'parent' => 'dashboard'
        ],
        'dashboard2' => [
            'icon' => '~/img/dashboard.svg',
            'name' => 'Dashboard',
            'url' => 'dashboard',
            'order' => 2
        ]
    ]

];

?>
