<?php

class visit {

    public static function add() {

        $now = new DateTime('now');

        $ip = self::getIP();

        $where = [
            'visit_ip' => $ip,
            'visit_date' => $now->format('Y-m-d')
        ];

        $entry = db()->from('visits')->where($where)->fetch();

        if($entry) {

            $set = [
                'visit_hits' => $entry->visit_hits + 1
            ];

            return db()->update('visits')->set($set)->where('visit_id',  $entry->visit_id)->execute();

        }

        $values = [
            'visit_ip' => $ip,
            'visit_hits' => 1,
            'visit_date' => $now->format('Y-m-d')
        ];

        return db()->insertInto('visits')->values($values)->execute();

    }

    public static function getIP() {

        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }

    }

}

?>
