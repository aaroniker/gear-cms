<?php

return [

    'name' => 'system',

    'run' => function($app) {
        $app->assets->addJS('~/scripts/dist/system.js', 'afterVue');
    },

    'admin' => true,

    'register' => [
        'modules/*/index.php'
    ],

    'required' => [
        'auth',
        'database'
    ]

];

?>
