<?php

    function time_since($since) {

        $datetime = new DateTime('now');
        $actual = new DateTime($since);

        $since = $datetime->getTimestamp() - $actual->getTimestamp();

        $chunks = array(
            array(60 * 60 * 24 * 365 , 'Jahr', 'Jahren'),
            array(60 * 60 * 24 * 30 , 'Monat', 'Monaten'),
            array(60 * 60 * 24 * 7, 'Woche', 'Wochen'),
            array(60 * 60 * 24 , 'Tag', 'Tagen'),
            array(60 * 60 , 'Stunde', 'Stunden'),
            array(60 , 'Minute', 'Minuten'),
            array(1 , 'Sekunde', 'Sekunden')
        );

        for($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = array($chunks[$i][1], $chunks[$i][2]);
            if(($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name[0] : $count.' '.$name[1];

        return 'vor '.$print;

    }

?>
