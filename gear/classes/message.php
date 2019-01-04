<?php

class message {

    protected $app;

    protected $sessionName = 'messages';

    function __construct($app) {

        $this->app = $app;

        if(ajax::is()) {

            if(ajax::get('method') == 'getMessages') {
                ajax::addReturn(json_encode($this->getAll()));
            }

            if(ajax::get('method') == 'setMessage') {
                $array = ajax::get('message');
                $this->add($array['message'], $array['type'], $array['stay']);
            }

            if(ajax::get('method') == 'deleteMessage') {
                $index = ajax::get('index');
                $this->delete($index);
            }

        }

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
