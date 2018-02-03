<?php

return [

    'name' => 'system/theme',

    'run' => function($app) {

        $app->assets->addJS('/gear/assets/vue/dist/vue.js');
        $app->assets->addJS('/gear/system/scripts/dist/system.js', 'afterVue');

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
