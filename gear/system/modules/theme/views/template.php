<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?= $assets->get('~/img/favicon-32x32.png', $module); ?>">
    <!--[if IE]>
        <link rel="shortcut icon" href="<?= $assets->get('~/img/favicon.ico', $module); ?>">
    <![endif]-->

    <title><?= $app->view->global('title'); ?></title>

    <script>
        var $gear = <?= json_encode((array)$app->config->get('system')); ?>;
        var $lang = <?= json_encode((array)$app->lang->getArray()); ?>;
    </script>

    <?= $assets->getCSS(); ?>

</head>
<body>

    <div id="gear">

        <section id="sidebar">
            <div class="inner">
                <a class="logo" href="<?= $app->config->get('system')['url'].'/'.$app->config->get('system')['adminURL']; ?>">
                    <img src="<?= $assets->get('~/img/logo.svg', $module); ?>">
                </a>
                <nav>
                    <h5>Menu</h5>
                    <ul>
                    <?php
                        foreach($app->admin->getMenus('main') as $name => $item) {
                            $active = ($route->fullURL() == $route->getURL($item['url'])) ? ' class="active"' : '';
                            echo '
                            <li'.$active.'>
                                <a href="'.$route->getURL($item['url']).'">
                                    <div class="icon">
                                        '.$item['icon'].'
                                    </div>
                                    '.__($item['name']).'
                                </a>
                            ';
                            if(isset($item['sub'])) {
                                echo '<ul>';
                                foreach($item['sub'] as $sub) {
                                    $activeSub = ($route->fullURL() == $route->getURL($sub['url'])) ? ' class="active"' : '';
                                    echo '
                                    <li'.$activeSub.'>
                                        <a href="'.$route->getURL($sub['url']).'">
                                            '.__($sub['name']).'
                                        </a>
                                    </li>
                                    ';
                                }
                                echo '</ul>';
                            }
                            echo '
                            </li>
                            ';
                        }
                    ?>
                    </ul>
                </nav>
            </div>
        </section>

        {{ messages }}

        <?= $app->view->get('content') ?>

    </div>

    <?= $assets->getJS(); ?>
    <?= $assets->getJS('vue'); ?>
    <?= $assets->getJS('afterVue'); ?>

</body>
</html>
