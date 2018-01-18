<?php

return [

    'name' => 'auth',

    'run' => function($app) {
        var_dump($this->config());
    },

    'autoload' => [
        'classes'
    ],

    'config' => [
        'test' => '121'
    ]

];

?>
