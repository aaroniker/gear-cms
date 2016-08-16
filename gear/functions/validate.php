<?php

    function validate($data, $addRules = []) {

        $rules = [
            'email' => 'valid_email'
        ];

        $rulesArray = array_merge($rules, $addRules);

        validate::set_field_name("email", lang::get('email'));
        validate::set_field_name("password", lang::get('password'));

        $validate = new validate();

        $validate->validation_rules($rulesArray);

        $is_valid = ($validate->run($data) === false) ? false : true;

        return [$is_valid, $validate->get_readable_errors()];

    }

?>
