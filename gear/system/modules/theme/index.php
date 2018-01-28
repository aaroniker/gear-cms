<?php

return [

    'name' => 'system/theme',

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
