<?php

return [

    'name' => 'system/theme',

    'run' => function($app) {
        $app->assets->addCSS('~/styles/dist/style.css');
        $app->assets->addJS('/gear/assets/vue/dist/vue.js');
        $app->assets->addJS('~/scripts/dist/messages.js', 'afterVue');
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
