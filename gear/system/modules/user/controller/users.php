<?php

class usersController extends controller {

    public function index() {

        return [
            'view' => [
                'global' => [
                    'title' => 'Users',
                    'add' => '/users/add'
                ],
                'file' => 'views/list'
            ]
        ];

    }

    public function add() {

        return [
            'view' => [
                'global' => [
                    'title' => 'Users'
                ],
                'file' => 'views/add'
            ]
        ];

    }

}

?>
