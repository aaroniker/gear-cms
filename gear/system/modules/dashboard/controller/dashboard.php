<?php

class dashboardController {

    public function index() {

        return [
            'view' => [
                'global' => [
                    'title' => 'Dashboard'
                ],
                'file' => 'views/dashboard'
            ]
        ];

    }

}

?>
