<?php

return [

    'name' => 'dashboard',

    'run' => function($app) {

    },

    'routes' => [
        '/' => [
            'name' => 'dashboard',
            'controller' => 'controller/dashboard'
        ]
    ]

];

?>
