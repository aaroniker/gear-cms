<?php

return [

    'name' => 'system',

    'admin' => true,

    'register' => [
        'modules/*/index.php'
    ],

    'required' => [
        'route',
        'auth',
        'database'
    ]

];

?>
