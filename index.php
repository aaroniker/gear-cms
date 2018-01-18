<?php

    if(version_compare($version = PHP_VERSION, $required = '5.6', '<=')) {
        exit(sprintf('Gear CMS needs at least <strong>PHP %s</strong> (Current: <strong>PHP %s</strong>).', $required, $version));
    }

    $path = __DIR__;

    $env = 'system';

    if(!$configFile = realpath($path.'/gear/config.json')) {
        $env = 'installer';
    }

    include($path.'/gear/classes/dir.php');
    new dir();

    include(dir::classes('autoload.php'));

    autoload::register();

    require_once($path.'/gear/'.$env.'/boot.php');

?>
