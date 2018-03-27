<?php

use Medoo\Medoo;

return [

    'name' => 'database',

    'run' => function($app) {
        $app->db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $app->config->get('database')['name'],
            'server' => $app->config->get('database')['host'],
            'username' => $app->config->get('database')['user'],
            'password' => $app->config->get('database')['password'],
            'prefix' => $app->config->get('database')['prefix'],
            'port' => $this->app->config->get('database')['port']
        ]);
    }

];

?>
