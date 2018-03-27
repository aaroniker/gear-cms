<?php

class authAttempt extends auth {

    protected $app;
    protected $module;

    public function __construct($app, $module) {

        $this->app = $app;
        $this->module = $module;

        return $this;

    }

    protected function add() {
        $ip = parent::getIP();
        $expire = date("Y-m-d H:i:s", strtotime($this->module->config('attempts')['ban']));
        return $this->app->db->insert($this->module->config('attempts')['table'], [
            'ip' => $ip,
            'expire' => $expire
        ]);
    }

    protected function delete($ip, $all = false) {
        if($all) {
            return $this->app->db->delete($this->module->config('attempts')['table'], [
                "AND" => [
                    'ip' => $ip
                ]
            ]);
        }
        $attempts = $this->app->db->select($this->module->config('attempts')['table'], [
            'id',
            'expire'
        ], [
            'ip' => $ip
        ]);
        foreach($attempts as $attempt) {
            $expiredate = strtotime($attempt['expire']);
            $currentdate = strtotime(date("Y-m-d H:i:s"));
            if($currentdate > $expiredate) {
                $this->app->db->delete($this->module->config('attempts')['table'], [
                    "AND" => [
                        'id' => $attempt['id']
                    ]
                ]);
            }
        }
    }

    public function isBlocked() {
        $ip = parent::getIP();
        $this->delete($ip, false);
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        $attempts = $this->app->db->select($this->module->config('attempts')['table'], [
            'expire'
        ], [
            'ip' => $ip
        ], [
            'ORDER' => 'expire DESC'
        ]);
        if(count($attempts) < intval($this->module->config('attempts')['count'])) {
            return false;
        }
        return $this->getRemainBlock($attempts[0]['expire']);
    }

    public function getRemainBlock($expireLast) {

        $currentdate = new DateTime('now');
        $expireLast = new DateTime($expireLast);

        $diff = $currentdate->diff($expireLast);

        return [
            'minutes' => $diff->format("%I"),
            'seconds' => $diff->format("%S")
        ];

    }

}

?>
