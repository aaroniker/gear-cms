<?php

    $config = new config($configFile);

    $app = new application($config);

    date_default_timezone_set($config->get('timezone', 'Europe/Berlin'));

    $app->modules->register([
        'gear/installer/index.php',
        'gear/system/index.php',
        'extensions/*/*/index.php',
        'gear/modules/*/index.php'
    ], $path);

    $app->modules->load();

    $app->boot();

?>
