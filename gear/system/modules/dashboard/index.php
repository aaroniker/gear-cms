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
    ]

];

?>
