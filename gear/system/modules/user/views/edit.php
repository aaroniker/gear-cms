<div class="lightBox light">
<?php

    $user = ($app->user->getUser($id)) ? $app->user->getUser($id) : [
        'username' => null,
        'email' => null
    ];

    $form = new form();

    $form->addProp('route', '/user/edit');

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

    $form->addRaw('<button type="submit" class="btn">'.((!$app->user->getUser($id)) ? __('Add') : __('Save')).'</button>');

    echo $form->show();

?>
</div>
