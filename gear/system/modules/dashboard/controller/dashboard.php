<?php

class dashboardController {

    public function index() {

        return [
            'view' => [
                'title' => 'Dashboard',
                'file' => 'views/dashboard'
            ]
        ];

    }

}

?>
