<?php

    include('gear/classes/dir.php');

    new dir();

    $env = 'frontend';

    include(dir::gear('bootstrap.php'));

    echo config::get('content');

?>
