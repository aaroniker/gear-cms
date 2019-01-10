<?php

class usersController extends controller {

    public function index() {

        return [
            'view' => [
                'global' => [
                    'title' => 'Users',
                    'add' => '/users/edit'
                ],
                'file' => 'views/list'
            ]
        ];

    }

    public function edit($id = 0) {

        return [
            'view' => [
                'global' => [
                    'title' => 'Users'
                ],
                'vars' => [
                    'id' => $id
                ],
                'file' => 'views/edit'
            ]
        ];

    }

}

?>
