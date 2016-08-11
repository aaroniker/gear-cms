<section id="user">

    <header>

        <h2><?=lang::get('edit'); ?></h2>

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

        $field = $form->addTextField('username', $this->model->username);
	    $field->fieldName(lang::get('username'));
        $field->fieldValidate();

        $field = $form->addTextField('email', $this->model->email);
	    $field->fieldName(lang::get('email'));
        $field->fieldValidate('valid_email|required');

        if($this->model->id != user::current()->id) {

    	    $field = $form->addRadioInlineField('status', $this->model->status);
            $field->fieldName(lang::get('status'));

            $field->add(1, lang::get('active'));
            $field->add(0, lang::get('blocked'));

        } else {

    	    $field = $form->addRawField('<p class="static">'.lang::get('status_change_own').'</p>');
    	    $field->fieldName(lang::get('status'));

        }

        $field = $form->addSelectField('permissionID', $this->model->permissionID);
        $field->fieldName(lang::get('permissions'));
        $field->add(0, lang::get('admin'));

        foreach(PermissionModel::getAllFromDb() as $entry) {
            $field->add($entry->id, $entry->name);
        }

        if($this->model->id == user::current()->id) {
            $field->addAttribute('disabled');
        }

        if($form->isSubmit()) {

            if($form->validation()) {

			    $this->model->save($form->getAll());

                echo message::success(lang::get('user_edited'));

		    } else {
			    echo $form->getErrors();
		    }

	    }

    ?>

    <div class="columns">
        <div class="md-6">
            <?=$form->show(); ?>
        </div>
    </div>

</section>
