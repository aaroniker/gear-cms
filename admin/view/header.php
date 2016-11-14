<div id="left">
    <div class="inner">
        <div class="wrap">

            <div class="top clear">
                <a class="logo" href="<?=url::admin('dashboard'); ?>">
                    <img src="<?=url::assets('img/logo.svg'); ?>" alt="Gear Logo">
                </a>
            </div>

            <div class="user">
                <div class="inner clear">
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
                                <a href="?logout=1" data-tooltip="<?=lang::get('logout'); ?>">
                                    <i class="icon icon-log-out"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <?php
                $menu = admin::getMenu();
                if($menu):
            ?>
            <nav id="nav">
                <ul>
                <?php
                    foreach($menu as $url => $array):

                        $openArr = [];
                        if(user::current()->openMenu) {
                            $openArr = unserialize(user::current()->openMenu);
                        }
                        $open = (isset($openArr[$url])) ? true : false;

                        $class = ($array['class']) ? $array['class'] : '';
                        $class = ($open) ? $class.' drop dropFade' : $class;

                        $sub = admin::getSubmenu($url);
                        $drop = ($sub && count($sub) > 1) ? '<span data-id="'.$url.'"></span>' : '';

                        echo '
                        <li class="'.$class.'">
                            '.$drop.'
                            <a href="'.url::admin($url).'">
                                <i class="icon icon-'.$array['icon'].'"></i>
                                '.$array['name'].'
                            </a>
                        ';
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

            <div class="info clear">
                <div class="fl-left">
                    <?=sprintf(lang::get('version'), config::get('version')); ?>
                </div>
                <nav>
                    <ul class="clear">
                        <li>
                            <a href="http://gearcms.org" data-tooltip="<?=lang::get('website'); ?>" target="_blank">
                                <i class="icon icon-earth"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</div>

<div id="expand">
    <i class="icon icon-navicon-round"></i>
</div>

<header id="head" class="clear">

    <div id="messages"></div>

    <?php if(admin::$subpage): ?>
    <h2>
        <a href="<?=url::admin(admin::$url); ?>">
            <?=admin::$page; ?>
        </a>
    </h2>
    <h1 v-html="headline"><?=admin::$subpage; ?></h1>
    <?php else: ?>
    <h1><?=admin::$page; ?></h1>
    <?php endif; ?>

    <?php if(admin::$search): ?>
    <div v-if="showSearch" class="search">
        <input type="text" v-model="search">
    </div>
    <?php endif; ?>

    <nav>
        <ul class="clear">
            <?php
                if(count(admin::getButtons())):
                    foreach(admin::getButtons() as $button) {
                        echo '<li>';
                        echo $button;
                        echo '</li>';
                    }
                endif;
            ?>
        </ul>
    </nav>

</header>

<main>

    <nav>
    <?php
        $sub = admin::getSubmenu(admin::$url);
        if($sub):
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
    ?>
    </nav>

    <div class="buttons clear">
        <?php
            if(count(admin::getButtons())):
                echo '<nav><ul class="clear">';
                foreach(admin::getButtons() as $button) {
                    echo '<li>';
                    echo $button;
                    echo '</li>';
                }
                echo '</ul></nav>';
            endif;
        ?>
    </div>

    <?=config::get('system'); ?>
