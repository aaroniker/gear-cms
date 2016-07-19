<?php

    function html_convertAttribute($attributes) {

        $return = '';

        foreach($attributes as $key => $val) {

            if(is_int($key)) {

                $return .= ' '.$val;

            } else {

                if(is_array($val)) {
                    $val = implode(' ', $val);
                }

                $return .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($val).'"';

            }

        }

        return $return;

    }

?>
