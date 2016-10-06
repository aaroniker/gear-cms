<?php
    $base = '../';

    include($base.'gear/bootstrap.php');

    theme::addCSS('https://cdn.jsdelivr.net/animatecss/3.5.2/animate.min.css');
    theme::addCSS(url::assets('css/style.css'));

    theme::addJS('https://cdn.jsdelivr.net/jquery/3.1.1/jquery.min.js');
    theme::addJS(url::assets('js/vue.js'));
    theme::addJS(url::assets('js/session.js'));
    theme::addJS(url::assets('js/sortable.js'));
    theme::addJS(url::assets('js/directives.js'));
    theme::addJS(url::assets('js/app.js'));
    theme::addJS(url::assets('js/layout.js'));

    userPerm::add('content[index]', lang::get('content[index]'));
    userPerm::add('content[media]', lang::get('content[media]'));
    userPerm::add('user[index]', lang::get('user[index]'));
    userPerm::add('user[index][add]', lang::get('user[index][add]'));
    userPerm::add('user[index][edit]', lang::get('user[index][edit]'));
    userPerm::add('user[index][delete]', lang::get('user[index][delete]'));
    userPerm::add('user[permissions]', lang::get('user[permissions]'));
    userPerm::add('extensions[index]', lang::get('extensions[index]'));

    ob_start();

    new application('admin');

    $content = ob_get_contents();

    ob_end_clean();

    config::add('content', $content);

    if(ajax::is()) {

        ajax::getMessages();
        ajax::deleteMessages();

        echo ajax::getReturn();

        exit();

    }

    if(userSession::loggedIn()) {

        admin::addMenu(lang::get('dashboard'), 'dashboard');
        admin::addSubmenu(lang::get('overview'), 'index', 'dashboard');

        admin::addMenu(lang::get('content'), 'content');
        admin::addSubmenu(lang::get('pages'), 'index', 'content');
        admin::addSubmenu(lang::get('menus'), 'menus', 'content');
        admin::addSubmenu(lang::get('media'), 'media', 'content');

        admin::addMenu(lang::get('user'), 'user');
        admin::addSubmenu(lang::get('list'), 'index', 'user');
        admin::addSubmenu(lang::get('permissions'), 'permissions', 'user');

        admin::addMenu(lang::get('extensions'), 'extensions');
        admin::addSubmenu(lang::get('plugins'), 'index', 'extensions');

        admin::addMenu(lang::get('system'), 'system');
        admin::addSubmenu(lang::get('settings'), 'index', 'system');

        include(dir::view('head.php'));

        include(dir::view('header.php'));

        echo config::get('content');

        // Components
        include(dir::components('data-table.html'));
        include(dir::components('file-table.html'));
        include(dir::components('modal.html'));

        include(dir::view('footer.php'));

    } else {

        admin::$page = lang::get('login');

        include(dir::view('head.php'));

        echo config::get('content');

        include(dir::view('footer.php'));

    }

?>
