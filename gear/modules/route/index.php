<?php

return [

    'name' => 'route',

    'main' => function($app) {

        $app->hook::bind('boot', function($app) {
            $app->route = new route($app);
        });

    },

    'autoload' => [
        'classes'
    ]

];

?>
