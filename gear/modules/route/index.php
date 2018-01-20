<?php

return [

    'name' => 'route',

    'autoload' => [
        'classes'
    ],

    'hooks' => [
        'boot' => function($app) {
            $app->route = new route($app, $this);
        }
    ],

    'config' => [
        'url' => $this->app->config->get('system')['url'],
        'adminURL' => 'admin'
    ]

];

?>
