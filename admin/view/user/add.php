<section id="user">

    <header>

        <h2><?=lang::get('add'); ?></h2>

        <nav>
            <ul>
                <li>
                    <a href="<?=url::admin('user'); ?>" class="button border">
                        <?=lang::get('back'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('username', '');
	    $field->fieldName(lang::get('username'));
        $field->fieldValidate();

        $field = $form->addTextField('email', '');
	    $field->fieldName(lang::get('email'));
        $field->fieldValidate('valid_email|required');

	    $field = $form->addPasswordField('password', '');
        $field->fieldName(lang::get('password'));
        $field->fieldValidate();

	    $field = $form->addRadioInlineField('status', 1);
        $field->fieldName(lang::get('status'));
        $field->add(1, lang::get('active'));
        $field->add(0, lang::get('blocked'));

        $field = $form->addSelectField('permissionID', 0);
        $field->fieldName(lang::get('permissions'));
        $field->add(0, lang::get('admin'));

        foreach(PermissionModel::getAllFromDb() as $entry) {
            $field->add($entry->id, $entry->name);
        }

        if($form->isSubmit()) {

            if($form->validation()) {

			    extension::add('model_beforeInsert', function($data) {
    			    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    			    $data['password'] = $password;
    		        return $data;
			    });

			    $this->model->insert($form->getAll());

    			echo message::success(lang::get('user_added'));

		    } else {
			    echo $form->getErrors();
		    }

	    }

    ?>

    <div class="columns">
        <div class="md-9 lg-7">
            <?=$form->show(); ?>
        </div>
    </div>

</section>
