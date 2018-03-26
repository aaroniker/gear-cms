<?php

class dashboardController {

    public function index() {

        return [
            'view' => [
                'set' => [
                    'title' => 'Dashboard'
                ],
                'file' => 'views/dashboard'
            ]
        ];

    }

}

?>
