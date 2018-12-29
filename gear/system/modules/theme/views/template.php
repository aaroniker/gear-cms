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

    <div id="gear" class="interface">

        <section id="sidebar">
            <div class="inner">
                <a class="logo" href="<?= $app->config->get('system')['url'].'/'.$app->config->get('system')['adminURL']; ?>">
                    <svg>
                        <use xlink:href="#logo" />
                    </svg>
                </a>
                <nav>
                    <ul>
                    <?php
                        foreach($app->admin->getMenus('main') as $name => $item) {
                            echo '
                            <li'.$item['activeClass'].'>
                                <a href="'.$route->getURL($item['url']).'">
                                    '.$item['icon'].'
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

            <a href="<?= $app->config->get('system')['url']; ?>" class="link">
                <div>
                    <svg>
                        <use xlink:href="#linkUI" />
                    </svg>
                </div>
                <span><?= str_replace(['http://', 'https://'], '', $app->config->get('system')['url']); ?></span>
            </a>

            <messages></messages>

            <div class="user">
                <nav>
                    <ul>
                        <li>
                            <a href="<?= $route->getLink('login', ['logout']); ?>">
                                <svg>
                                    <use xlink:href="#outUI" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="panel">
                    <a href="" class="avatar"><?= $app->auth->getCurrentUser()['username'][0]; ?></a>
                </div>
            </div>

            <h1><?= __($app->view->global('title')); ?></h1>

            <div class="content">
                <?= $app->view->get('content') ?>
            </div>

        </section>

    </div>

    <?= $assets->getJS(); ?>
    <?= $assets->getJS('vue'); ?>
    <?= $assets->getJS('afterVue'); ?>

    <?php include('svg.php'); ?>

</body>
</html>
