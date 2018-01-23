<?php

return [

    'name' => 'user',

    'run' => function($app) {

    },

    'routes' => [
        '/login' => [
            'name' => 'login',
            'controller' => 'controller/login'
        ]
    ],

    'autoload' => [
        'classes'
    ],

    'config' => [
        'table' => 'users'
    ],

    'action' => [
        'boot-5' => function($app) {
            if(!$app->auth->isLogged() && $app->admin) {
                $app->route->redirect('login');
            }
        }
    ],

    'required' => [
        'auth'
    ]

];

?>
