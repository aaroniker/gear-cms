<?php

return [

    'name' => 'lang',

    'run' => function($app) {

        $app->lang = new lang($app, $this);

        function __($name) {
            global $app;
            return $app->lang->get($name);
        }

    },

    'autoload' => [
        'classes',
        'functions'
    ],

    'config' => [
        'dir' => 'langs',
        'default' => $this->app->config->get('system')['lang']
    ]

];

?>
