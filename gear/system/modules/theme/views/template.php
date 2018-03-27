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

    <title><?= __($app->view->global('title')); ?></title>

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
                    <ul>
                    <?php
                        foreach($app->admin->getMenus('main') as $name => $item) {
                            echo '
                            <li'.$item['activeClass'].'>
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
                                    echo '
                                    <li'.$sub['activeClass'].'>
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

        <section id="main">

            <div class="header">
                <a href="<?= $app->config->get('system')['url']; ?>" class="link">
                    <div><?= $assets->getIcon('~/img/link.svg', $module); ?></div>
                    <span><?= str_replace(['http://', 'https://'], '', $app->config->get('system')['url']); ?></span>
                </a>
                <messages></messages>
                <div class="user">
                    <nav>
                        <ul>
                            <li><a href="<?= $route->getLink('login', ['logout']); ?>"><?= $assets->getIcon('~/img/logout.svg', $module); ?></a></li>
                        </ul>
                    </nav>
                    <div class="panel">
                        <div class="avatar"><?= $app->auth->getCurrentUser()['username'][0]; ?></div>
                    </div>
                </div>
            </div>

            <div class="toolbar">
                <h1><?= __($app->view->global('title')); ?></h1>
                <div class="filler"></div>
                <div class="text2">
                    <a href="" class="btn">Button</a>
                </div>
            </div>

            <div class="content">
                <?= $app->view->get('content') ?>
            </div>

        </section>

    </div>

    <?= $assets->getJS(); ?>
    <?= $assets->getJS('vue'); ?>
    <?= $assets->getJS('afterVue'); ?>

</body>
</html>
