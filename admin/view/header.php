<header id="head">

    <div class="container">

        <div class="inner clear">

            <div id="messages"></div>

            <div class="expand">
                <i class="icon icon-navicon-round"></i>
            </div>

            <h1><?=admin::$page; ?></h1>

            <div class="user clear">

                <nav>
                    <ul>
                        <li>
                            <a href="?logout=1">
                                <i class="icon icon-log-out"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <a href="<?=url::admin('user', ['index', 'edit']); ?>" class="profile">
                    <?=user::getAvatar(36, true); ?>
                    <span>
                        <?=user::current()->username; ?>
                    </span>
                </a>

            </div>

        </div>

    </div>

    <div id="nav">

        <?php
            $submenu = admin::getSubmenu();

            if($submenu):
        ?>

        <div class="submenu">
            <div class="container">
                <nav>
                    <ul>
                        <?php

                            foreach($submenu as $url => $array) {

                                $class = ($array['class']) ? ' class="'.$array['class'].'"' : '';

                                echo '
                                    <li'.$class.'>
                                        <a href="'.url::admin($url).'">'.$array['name'].'</a>
                                    </li>
                                ';

                            }

                        ?>
                    </ul>
                </nav>
            </div>
        </div>

        <?php
            endif;

            $menu = admin::getMenu();

            if($menu):
        ?>

        <div class="menu">
            <div class="container">
                <nav>
                    <ul>
                        <?php
                            foreach($menu as $url => $array) {

                                $class = ($array['class']) ? ' class="'.$array['class'].'"' : '';

                                echo '
                                    <li'.$class.'>
                                        <a href="'.url::admin($url).'">'.$array['name'].'</a>
                                    </li>
                                ';

                            }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>

        <?php
            endif;
        ?>

    </div>

</header>

<div class="container">
    <main>

        <?php echo config::get('system'); ?>
