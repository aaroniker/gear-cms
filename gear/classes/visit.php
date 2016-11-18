<?php

class visit {

    public static function add() {

        if(!self::checkBot()) {

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

        return false;

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

    public static function checkBot() {
        return (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT']));
    }

    public static function getLast($count) {

        $now = new DateTime('now');

        $return = [];

        for($i = 0; $i < $count; $i++) {

            $date = $now->modify("-1 day");
            $visits = 0;

            $where = [
                'visit_date' => $date->format('Y-m-d')
            ];

            $entry = db()->from('visits')->where($where)->fetchAll();

            if($entry) {
                foreach($entry as $val) {
                    $visits = $visits + 1;
                }
            }

            $return[$date->format('d.m')] = $visits;

        }

        return $return;

    }

}

?>
