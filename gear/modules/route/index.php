<?php

return [

    'name' => 'route',

    'run' => function($app) {

        $app->hook::bind('boot', function($app) {
            $app->route = new route($app);
        });

    },

    'autoload' => [
        'classes'
    ]

];

?>
