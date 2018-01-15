<?php

    if(version_compare($version = PHP_VERSION, $required = '5.6', '<=')) {
        exit(sprintf('Gear CMS needs at least <strong>PHP %s</strong> (Current: <strong>PHP %s</strong>).', $required, $version));
    }

?>
