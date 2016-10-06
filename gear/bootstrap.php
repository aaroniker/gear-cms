<?php

    if(version_compare($version = PHP_VERSION, $required = '5.4', '<')) {
        exit(sprintf('You are running PHP %s, but Gear needs at least <strong>PHP %s</strong>.', $version, $required));
    }

    ob_start();

    ob_implicit_flush(0);
    mb_internal_encoding('UTF-8');

    include($base.'gear/classes/dir.php');

    new dir($base);

    include(dir::classes('autoload.php'));

    autoload::register();
    autoload::addDir(dir::model());

    include(dir::functions('time.php'));
    include(dir::functions('html.php'));
    include(dir::functions('validate.php'));

    if(!defined('INSTALL')) {

        new config();

        userSession::init();

        $DB = config::get('DB');

        $pdo = new PDO('mysql:host=' . $DB['host'] . ';dbname=' . $DB['database'] . ';charset=utf8', $DB['user'], $DB['password'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]);

        $db = new FluentPDO($pdo);

        function db() {
            global $db;
            return $db;
        }

        unset($DB, $pdo);

        lang::setLang(config::get('lang'));

        cache::setCache(config::get('cache'));

    }

    date_default_timezone_set(config::get('timezone', 'Europe/Berlin'));

    new userLogin();

    $system = ob_get_contents();

    ob_end_clean();

    config::add('system', $system);

?>
