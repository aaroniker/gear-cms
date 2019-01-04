<?php

class usersController extends controller {

    public function index() {

        return [
            'view' => [
                'set' => [
                    'title' => 'Users'
                ],
                'file' => 'views/list'
            ]
        ];

    }

}

?>
