<?php

class filter {

    public static function xss($value) {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

}

?>
