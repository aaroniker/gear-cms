<?php

return [

    'name' => 'system',

    'main' => function($app) {

    },

    'register' => [
        'modules/*/index.php'
    ],

    'required' => [
        'route'
    ],

    'hooks' => [
    ],

    'autoload' => [
    ]

];

?>
