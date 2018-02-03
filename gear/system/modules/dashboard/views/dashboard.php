<?php $app->assets->addJS('/gear/system/modules/dashboard/scripts/dist/test.js', 'vue'); ?>

dashboard <a href="<?= $route->getLink('login', ['logout']); ?>">Logout</a>

<test-comp></test-comp>
