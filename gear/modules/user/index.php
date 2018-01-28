<?php

return [

    'name' => 'user',

    'run' => function($app) {

        $app->user = new user($app, $this);

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
                $return = $app->auth->login(type::post('email'), type::post('password'), type::post('remember'));
                if(!$return['error']) {
                    $app->route->redirect('dashboard');
                } else {
                    echo $return['message'];
                }
            }

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
