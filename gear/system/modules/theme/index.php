<?php

return [

    'name' => 'system/theme',

    'run' => function($app) {
        $app->assets->addJS('/gear/assets/vue/dist/vue.js');
        $app->assets->addCSS('~/styles/dist/style.css');
    },

    'filter' => [
        'application.show' => function($content) {
            $file = ($this->app->auth->isLogged()) ? 'template.php' : 'login.php';
            $view = new view($this->path.'/views/'.$file, [
                'app' => $this->app
            ]);
            return $view->render();
        }
    ]

];

?>
