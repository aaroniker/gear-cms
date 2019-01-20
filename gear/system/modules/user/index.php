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
        '/user/edit' => [
            'method' => 'POST',
            'callback' => function($app) {
                $user = api::getPost('data');
                if(isset($user['id'])) {
                    $app->user->editUser($user['id'], $user['username'], $user['email']);
                    $app->message->add('User edited');
                } else {
                    $app->user->addUser($user['username'], $user['email'], $app->auth->getHash($user['password']));
                    $app->message->add('User added');
                }
            }
        ]
    ],

    'autoload' => [
        'classes'
    ],

    'config' => [
        'table' => 'users'
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
