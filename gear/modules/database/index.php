<?php

return [

    'name' => 'database',

    'run' => function($app) {
        $app->db = new sql($app, $this);
    },

    'autoload' => [
        'classes'
    ],

    'config' => [
        'database' => [
            'host' => $this->app->config->get('database')['host'],
            'port' => $this->app->config->get('database')['port'],
            'user' => $this->app->config->get('database')['user'],
            'password' => $this->app->config->get('database')['password'],
            'name' => $this->app->config->get('database')['name'],
            'prefix' => $this->app->config->get('database')['prefix']
        ]
    ]

];

?>
