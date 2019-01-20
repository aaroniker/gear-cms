<?php

    $form = new form();

    $form->addProp('route', '/auth/login');
    $form->addProp('redirect', '/dashboard');

    $form->addText('email', null, [
        'placeholder' => __('Email')
    ]);

    $form->addPassword('password', null, [
        'placeholder' => __('Password')
    ]);

    $form->addCheckbox('remember')->add(1, __('Stay logged in'));

    $form->addRaw('
        <button class="btn block" type="submit">
            <svg>
                <use xlink:href="#lockUI" />
            </svg>
            '.__('Login').'
        </button>
    ');

    echo $form->show();

?>
