<?php

    include('../gear/classes/dir.php');

    new dir('../');

    include(dir::gear('bootstrap.php'));

    theme::addCSS('admin/assets/css/style.css', true);

    theme::addJS('https://code.jquery.com/jquery-3.0.0.min.js');
    theme::addJS('https://cdn.jsdelivr.net/vue/1.0.26/vue.min.js');
    theme::addJS('admin/assets/js/app.js', true);

    ob_start();

    new application('admin');

    $content = ob_get_contents();

    ob_end_clean();

    config::add('content', $content);

    if(ajax::is()) {

        echo ajax::getReturn();

        exit();

    }

    if(userLogin::isLogged()) {

        admin::addMenu(lang::get('dashboard'), '');

        admin::addMenu(lang::get('content'), 'content');
        admin::addSubmenu(lang::get('pages'), '', 'content');
        admin::addSubmenu(lang::get('storage'), 'storage', 'content');

        admin::addMenu(lang::get('user'), 'user');
        admin::addSubmenu(lang::get('list'), '', 'user');
        admin::addSubmenu(lang::get('rights'), 'rights', 'user');
        admin::addSubmenu(lang::get('groups'), 'groups', 'user');

        admin::addMenu(lang::get('plugins'), 'plugins');
        admin::addMenu(lang::get('system'), 'system');

        include(dir::view('head.php'));

        include(dir::view('header.php'));

        echo config::get('content');

        // Components
        include(dir::components('data-table.html'));
        include(dir::components('file-table.html'));

        include(dir::view('footer.php'));

    } else {

        include(dir::view('head.php'));

        echo config::get('content');

        include(dir::view('footer.php'));

    }

?>
