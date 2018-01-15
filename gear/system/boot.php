<?php

    $config = new config($configFile);

    date_default_timezone_set($config->get('timezone', 'Europe/Berlin'));

    $db = $config->get('database');

    sql::connect($db['host'], $db['port'], $db['user'], $db['password'], $db['database'], $db['prefix']);

    echo 'system';

?>
