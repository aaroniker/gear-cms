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
        'boot' => function($app) {
            if(!$app->route->controller) {
                $app->route->redirect('dashboard');
            }
        }
    ]

];

?>
