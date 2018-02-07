<?php

class message {

    protected $sessionName = 'messages';

    function __construct() {

        if(ajax::is()) {

            if(ajax::get('method') == 'getMessages') {
                ajax::addReturn(json_encode($this->getAll()));
            }

            if(ajax::get('method') == 'setMessage') {
                $array = type::post('message', 'string', '');
                $this->add($array['message'], $array['class'], $array['stay']);
            }

            if(ajax::get('method') == 'deleteMessage') {
                $index = type::post('index', 'int', 0);
                $this->delete($index);
            }

        }

    }

    public function add($message, $class = 'success', $stay = false) {
        $_SESSION[$this->sessionName][$this->getIndex()] = [
            'message' => $message,
            'class' => $class,
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
                    'class' => $val['class'],
                    'stay' => $val['stay'],
                    'index' => $index
                ];
            }
        }

        return $return;

    }

    public function delete($index) {
        if(isset($_SESSION[$this->sessionName][$index])) {
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
