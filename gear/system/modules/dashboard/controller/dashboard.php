<?php

class dashboardController {

    public function index() {

        return [
            'view' => [
                'set' => [
                    'title' => 'Dashboard',
                    'description' => 'A simple description'
                ],
                'file' => 'views/dashboard'
            ]
        ];

    }

}

?>
