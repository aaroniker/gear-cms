<?php

    $config = new config($configFile);

    $app = new application($config);

    date_default_timezone_set($config->get('timezone', 'Europe/Berlin'));

    $db = $config->get('database');

    sql::connect($db['host'], $db['port'], $db['user'], $db['password'], $db['database'], $db['prefix']);

    $app->modules->register([
        'extensions/*/*/index.php',
        'gear/modules/*/index.php',
        'gear/installer/index.php',
        'gear/system/index.php'
    ], $path);

    $app->modules->load('system');

    $app->start();

    echo '<pre>';
    var_dump($app);
    echo '</pre>';

?>
