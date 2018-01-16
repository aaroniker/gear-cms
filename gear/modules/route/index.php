<?php

return [

    'name' => 'route',

    'main' => function($app) {

        $app->route = new route();

    },

    'autoload' => [
        'classes'
    ]

];

?>
