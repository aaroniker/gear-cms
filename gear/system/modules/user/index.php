<?php

return [

    'name' => 'user',

    'run' => function($app) {

        $app->user = new user($app, $this);

        $app->assets->addCSS('~/styles/dist/style.css');

    },

    'routes' => [
        '/login{/*}' => [
            'controller' => 'controller/login'
        ],
        '/users{/*}' => [
            'controller' => 'controller/users'
        ]
    ],

    'api' => [
        '/user/add' => [
            'method' => 'POST',
            'callback' => function($app) {
                $user = api::getPost('data');
                $app->user->addUser($user['username'], $user['email'], $app->auth->getHash($user['password']));
                $app->message->add('Benutzer hinzugefügt');
            }
        ],
        '/user/edit' => [
            'method' => 'POST',
            'callback' => function($app) {
                $user = api::getPost('data');
                $app->user->editUser($user['id'], $user['username'], $user['email']);
                $app->message->add('Benutzer bearbeitet');
            }
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
                    $app->route->redirect('/dashboard');
                }
            }

        }
    ],

    'required' => [
        'auth'
    ],

    'menu' => [
        'users' => [
            'icon' => 'usersIcon',
            'name' => 'Users',
            'url' => '/users',
            'active' => '/users{/*}',
            'order' => 15
        ]
    ]

];

?>
