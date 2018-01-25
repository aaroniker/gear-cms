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
        return $this->app->db->insertInto($this->module->config('attempts')['table'])->values([
            'ip' => $ip,
            'expire' => $expire
        ])->execute();
    }

    protected function delete($ip, $all = false) {
        if($all) {
            return $this->app->db->deleteFrom($this->module->config('attempts')['table'])->where('ip', $ip)->execute();
        }
        $attempts = $this->app->db->from($this->module->config('attempts')['table'])->where('ip', $ip)->fetchAll();
        foreach($attempts as $attempt) {
            $expiredate = strtotime($attempt->expire);
            $currentdate = strtotime(date("Y-m-d H:i:s"));
            if($currentdate > $expiredate) {
                $this->app->db->deleteFrom($this->module->config('attempts')['table'])->where('id', $attempt->id)->execute();
            }
        }
    }

    public function isBlocked() {
        $ip = parent::getIP();
        $this->delete($ip, false);
        $currentdate = strtotime(date("Y-m-d H:i:s"));
        $attempts = $this->app->db->from($this->module->config('attempts')['table'])->where('ip', $ip)->orderBy('expire DESC')->fetchAll();
        if(count($attempts) < intval($this->module->config('attempts')['count'])) {
            return false;
        }
        return $this->getRemainBlock($attempts[0]->expire);
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
