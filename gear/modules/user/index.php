<?php

return [

    'name' => 'user',

    'run' => function($app) {

        $app->user = new user($app, $this);

        $app->assets->addCSS('~/styles/dist/style.css');

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
        'application.boot-6' => function($app) {

            if(type::post('action') == 'login') {
                if($app->auth->login(type::post('email'), type::post('password'), type::post('remember'))) {
                    $app->route->redirect('dashboard');
                }
            }

            if(!$app->auth->isLogged() && $app->isAdmin) {
                $app->route->redirect('login');
            }

        }
    ],

    'required' => [
        'auth'
    ],

    'menu' => [
        'users' => [
            'icon' => '~/img/users.svg',
            'name' => 'Users',
            'url' => 'users',
            'active' => 'users(/*)?',
            'order' => 15
        ]
    ]

];

?>
