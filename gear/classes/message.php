<?php

class message {

    static protected function getMessage($message, $class) {

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

    static public function info($message) {

       return self::getMessage($message, 'info');

    }

    static public function error($message) {

        return self::getMessage($message, 'error');

    }

    static public function success($message) {

        return self::getMessage($message, 'success');

    }

}

?>
