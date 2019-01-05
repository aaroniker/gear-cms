<?php

class message {

    protected $app;

    protected $sessionName = 'messages';

    function __construct($app) {

        $this->app = $app;

        $this->app->router->get('/messages', function() {
            return json_encode($this->getAll());
        });

        $this->app->router->post('/addMessage', function() {
            $array = router::getPost('message');
            $this->add($array['message'], $array['type'], $array['stay']);
        });

        $this->app->router->delete('/message/{i}', function($index) {
            $this->delete($index);
        });

    }

    public function add($message, $type = 'success', $stay = false) {
        $_SESSION[$this->sessionName][$this->getIndex()] = [
            'message' => $message,
            'type' => $type,
            'stay' => $stay
        ];
    }

    public function getAll() {

        $return = [];
        $messages = type::session($this->sessionName);

        if(is_array($messages) && count($messages)) {
            foreach($messages as $index => $val) {
                $return[] = [
                    'message' => $val['message'],
                    'type' => $val['type'],
                    'stay' => $val['stay'],
                    'index' => $index
                ];
            }
        }

        return $return;

    }

    public function delete($index) {
        if($index == -1) {
            unset($_SESSION[$this->sessionName]);
        } elseif(isset($_SESSION[$this->sessionName][$index])) {
            unset($_SESSION[$this->sessionName][$index]);
        }
    }

    protected function getIndex($index = 0) {
        $index = (!$index) ? count(type::session($this->sessionName, 'array', [])) + 1 : $index;
        if(isset($_SESSION[$this->sessionName][$index])) {
            $index++;
            return $this->getIndex($index);
        }
        return $index;
    }

}

?>
