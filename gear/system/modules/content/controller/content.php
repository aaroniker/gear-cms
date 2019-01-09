<?php

class contentController extends controller {

    public function index() {

        return [
            'view' => [
                'global' => [
                    'title' => 'Content'
                ]
            ]
        ];

    }

}

?>
