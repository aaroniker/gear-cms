<?php

return [

    'name' => 'system/theme',

    'run' => function($app) {
        $app->assets->addCSS('~/styles/dist/style.css');
        $vue = ($app->config->get('system')['debug']) ? 'vue.js' : 'vue.min.js';
        $app->assets->addJS('/gear/assets/vue/dist/'.$vue);
        $app->assets->addJS('~/scripts/dist/messages.js', 'vue');
        $app->assets->addJS('~/scripts/dist/theme.js');
    },

    'filter' => [
        'application.show' => function($content) {
            $file = ($this->app->auth->isLogged()) ? 'template.php' : 'login.php';
            $view = new view($this->path.'/views/'.$file, [
                'app' => $this->app,
                'route' => $this->app->route,
                'module' => $this,
                'assets' => $this->app->assets
            ]);
            return $view->render();
        }
    ]

];

?>
