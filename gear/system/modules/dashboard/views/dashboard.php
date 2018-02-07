dashboard <a href="<?= $route->getLink('login', ['logout']); ?>"><?= __('Logout'); ?></a>

<test-comp></test-comp>

<pre>
    <?php
        var_dump(json_encode($app->message->getAll()));
    ?>
</pre>
