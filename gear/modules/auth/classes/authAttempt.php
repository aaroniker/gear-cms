<?php

class authAttempt extends auth {

    protected $app;
    protected $module;

    public function __construct($app, $module) {
        $this->app = $app;
        $this->module = $module;
    }

    protected function add() {
        $ip = parent::getIP();
        $expire = date("Y-m-d H:i:s", strtotime($this->module->config('attempts')['ban']));
        return sql::run()->insertInto($this->module->config('attempts')['table'])->values([
            'ip' => $ip,
            'expire' => $expire
        ])->execute();
    }

    protected function delete($ip, $all = false) {
        if($all) {
            return sql::run()->deleteFrom($this->module->config('attempts')['table'])->where('ip', $ip)->execute();
        }
        $attempts = sql::run()->from($this->module->config('attempts')['table'])->where('ip', $ip)->fetchAll();
        foreach($attempts as $attempt) {
            $expiredate = strtotime($attempt['expire']);
            $currentdate = strtotime(date("Y-m-d H:i:s"));
            if($currentdate > $expiredate) {
                sql::run()->deleteFrom($this->module->config('attempts')['table'])->where('id', $attempt['id'])->execute();
            }
        }
    }

}

?>
