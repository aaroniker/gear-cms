<?php

return [

    'name' => 'route',

    'autoload' => [
        'classes'
    ],

    'hooks' => [
        'boot' => function($app) {
            $app->route = new route($app);
        }
    ]

];

?>
