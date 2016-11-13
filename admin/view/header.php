<div id="left">

    <div class="top clear">
        <a class="logo" href="<?=url::admin('dashboard'); ?>">
            <img src="<?=url::assets('img/logo.svg'); ?>" alt="Gear Logo">
        </a>
    </div>

    <?php
        $menu = admin::getMenu();
        if($menu):
    ?>
    <nav id="nav">
        <ul>
        <?php
            foreach($menu as $url => $array):

                $class = ($array['class']) ? ' class="'.$array['class'].'"' : '';

                echo '
                <li'.$class.'>
                    <a href="'.url::admin($url).'">
                        <i class="icon icon-'.$array['icon'].'"></i>
                        '.$array['name'].'
                    </a>
                ';

                $sub = admin::getSubmenu($url);
                if($sub && count($sub) > 1):
                    echo '<ul>';

                    foreach($sub as $url => $array):

                        $class = ($array['class']) ? ' class="'.$array['class'].'"' : '';

                        echo '
                        <li'.$class.'>
                            <a href="'.url::admin($url).'">'.$array['name'].'</a>
                        </li>
                        ';

                    endforeach;

                    echo '</ul>';
                endif;

                echo '
                </li>
                ';

            endforeach;
        ?>
        </ul>
    </nav>
    <?php
        endif;
    ?>

    <div class="user clear">
        <a href="<?=url::admin('user', ['index', 'edit']); ?>" class="profile">
            <?php

                if(user::current()->avatar && file_exists(dir::media(user::current()->avatar))) {
                    echo '<div class="img" style="background-image: url('.url::media(user::current()->avatar).');"></div>';
                } else {
                    echo user::getAvatar(36, true);
                }

            ?>
            <span><?=user::current()->username; ?></span>
        </a>
        <nav>
            <ul class="clear">
                <li>
                    <a href="http://gearcms.org" data-tooltip="<?=lang::get('website'); ?>" target="_blank">
                        <i class="icon icon-earth"></i>
                    </a>
                </li>
                <li>
                    <a href="?logout=1" data-tooltip="<?=lang::get('logout'); ?>">
                        <i class="icon icon-log-out"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>

<header id="head" class="clear">

    <h1><?=admin::$page; ?></h1>

</header>

<main>

<div id="messages"></div>

<?=config::get('system'); ?>
