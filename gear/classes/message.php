<?php

class message {

    static public function getMessage($message, $class) {

        $return = '<div class="message '.$class.'">';

        if(is_array($message)) {

            if(count($message) > 1) {

                $return .= '<ol>';

                foreach($message as $list) {
                    $return .= '<li>'.$list.'</li>';
                }

                $return .= '</ol>';

            } else {

                $return .= $message[0];

            }

        } else {

            $return .= $message;

        }

        $return .= '</div>';

        return $return;

    }

    static protected function addMessage($message, $class) {

        userSession::init();

        $index = count($_SESSION['messages']) + 1;

        $_SESSION['messages'][$index] = [
            'message' => $message,
            'class' => $class
        ];

    }

    static public function info($message) {

        self::addMessage($message, 'info');

    }

    static public function error($message) {

        self::addMessage($message, 'error');

    }

    static public function success($message) {

        self::addMessage($message, 'success');

    }

}

?>
