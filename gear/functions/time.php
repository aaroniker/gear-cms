<?php

    function time_since($since) {

        $datetime = new DateTime('now');
        $actual = new DateTime($since);

        $since = $datetime->getTimestamp() - $actual->getTimestamp();

        $chunks = array(
            array(60 * 60 * 24 * 365 , lang::get('year'), lang::get('years')),
            array(60 * 60 * 24 * 30 , lang::get('month'), lang::get('months')),
            array(60 * 60 * 24 * 7, lang::get('week'), lang::get('weeks')),
            array(60 * 60 * 24 , lang::get('day'), lang::get('days')),
            array(60 * 60 , lang::get('hour'), lang::get('hours')),
            array(60 , lang::get('minute'), lang::get('minutes')),
            array(1 , lang::get('second'), lang::get('seconds'))
        );

        for($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = array($chunks[$i][1], $chunks[$i][2]);
            if(($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name[0] : $count.' '.$name[1];

        return sprintf(lang::get('ago'), $print);

    }

?>
