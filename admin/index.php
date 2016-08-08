<?php

    include('../gear/classes/dir.php');

    new dir('../');

    include(dir::gear('bootstrap.php'));

    theme::addCSS('admin/assets/css/style.css', true);

    theme::addJS('https://code.jquery.com/jquery-3.0.0.min.js');
    theme::addJS('https://cdn.jsdelivr.net/vue/1.0.26/vue.min.js');
    theme::addJS('admin/assets/js/app.js', true);

    userPerm::add('content[index]', lang::get('content[index]'));
    userPerm::add('content[media]', lang::get('content[media]'));
    userPerm::add('user[index]', lang::get('user[index]'));
    userPerm::add('user[index][add]', lang::get('user[index][add]'));
    userPerm::add('user[index][edit]', lang::get('user[index][edit]'));
    userPerm::add('user[index][delete]', lang::get('user[index][delete]'));
    userPerm::add('plugins[index]', lang::get('plugins[index]'));
    userPerm::add('plugins[index][install]', lang::get('plugins[index][install]'));
    userPerm::add('plugins[index][delete]', lang::get('plugins[index][delete]'));

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
        admin::addSubmenu(lang::get('media'), 'media', 'content');

        admin::addMenu(lang::get('user'), 'user');
        admin::addSubmenu(lang::get('list'), '', 'user');
        admin::addSubmenu(lang::get('permissions'), 'permissions', 'user');

        admin::addMenu(lang::get('plugins'), 'plugins');
        admin::addMenu(lang::get('system'), 'system');

        include(dir::view('head.php'));

        include(dir::view('header.php'));

        echo config::get('content');

        // Components
        include(dir::components('data-table.html'));
        include(dir::components('file-table.html'));
        include(dir::components('modal.html'));

        include(dir::view('footer.php'));

    } else {

        admin::addMenu(lang::get('login'), '');

        include(dir::view('head.php'));

        echo config::get('content');

        include(dir::view('footer.php'));

    }

?>
