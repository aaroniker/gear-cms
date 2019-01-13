<div class="lightBox light">
<?php

    $user = ($app->user->getUser($id)) ? $app->user->getUser($id) : [
        'username' => null,
        'email' => null
    ];

    $form = new form();

    $route = ($app->user->getUser($id)) ? '/user/edit' : '/user/add';

    $form->addProp('route', $route);

    $form->addText('username', $user['username'], [
        'col' => 'md-6'
    ])->fieldName('Username');

    $form->addText('email', $user['email'], [
        'col' => 'md-6'
    ])->fieldName('Email');

    if(!$app->user->getUser($id)) {
        $form->addPassword('password', null, [
            'col' => 'md-6'
        ])->fieldName('Password');
    } else {
        $form->addHidden('id', $id);
    }

    $label = (!$app->user->getUser($id)) ? __('Add') : __('Save');

    $form->addRaw('<button type="submit" class="btn">'.$label.'</button>');

    echo $form->show();

?>
</div>
