<?php

    include('gear/classes/dir.php');

    new dir();

    include(dir::gear('bootstrap.php'));

    new application('frontend');

?>
