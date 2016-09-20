<?php
    $base = '../';

    include($base.'gear/bootstrap.php');

    theme::addCSS('https://cdn.jsdelivr.net/animatecss/3.5.2/animate.min.css');
    theme::addCSS(url::assets('css/style.css'));

    theme::addJS('https://cdn.jsdelivr.net/vue/1.0.26/vue.js');
    theme::addJS('https://cdn.jsdelivr.net/jquery/3.1.0/jquery.min.js');
    theme::addJS('https://cdn.jsdelivr.net/gear-upload/0.2/gearUpload.min.js');
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
    userPerm::add('plugins[index]', lang::get('plugins[index]'));
    userPerm::add('plugins[index][install]', lang::get('plugins[index][install]'));
    userPerm::add('plugins[index][delete]', lang::get('plugins[index][delete]'));

    ob_start();

    new application('admin');

    $content = ob_get_contents();

    ob_end_clean();

    config::add('content', $content);

    if(ajax::is()) {

        if(type::post('method', 'string', '') == 'getMessages') {

            $messages = type::session('messages');

            if(is_array($messages) && count($messages)) {
                foreach($messages as $index => $val) {
                    $return = [
                        'html' => message::getMessage($val['message'], $val['class']),
                        'index' => $index
                    ];
                    ajax::addReturn(json_encode($return));
                    break;
                }
            }

        }

        if(type::post('method', 'string', '') == 'deleteMessage') {

            $messages = type::session('messages');
            $index = type::post('index');

            unset($messages[$index]);

            type::addSession('messages', $messages);

        }

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

        admin::addMenu(lang::get('plugins'), 'plugins');
        admin::addSubmenu(lang::get('list'), 'index', 'plugins');

        admin::addMenu(lang::get('system'), 'system');
        admin::addSubmenu(lang::get('general'), 'index', 'system');

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
