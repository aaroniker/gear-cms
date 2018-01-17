<?php

return [

    'name' => 'dashboard',

    'run' => function($app) {

    },

    'routes' => [
        '/' => [
            'include' => 'controller/dashboard'
        ]
    ]

];

?>
