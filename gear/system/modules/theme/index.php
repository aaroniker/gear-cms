<?php

return [

    'name' => 'system/theme',

    'run' => function($app) {
        $app->assets->addJS('/gear/assets/vue/dist/vue.js');
        $app->assets->addCSS('~/styles/dist/style.css');
    },

    'filter' => [
        'application.show' => function($content) {
            $view = new view($this->path.'/views/template.php', [
                'app' => $this->app
            ]);
            return $view->render();
        }
    ]

];

?>
