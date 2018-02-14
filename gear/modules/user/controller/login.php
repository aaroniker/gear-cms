<?php

class loginController extends controller {

    public function index() {

        if($this->app->auth->isLogged()) {
            $this->app->route->redirect('dashboard');
        }

        return [
            'view' => [
                'set' => [
                    'title' => 'Login'
                ],
                'file' => 'views/login'
            ]
        ];

    }

    public function logout() {

        $this->app->auth->logout();
        $this->app->route->redirect('login');

    }

}

?>
