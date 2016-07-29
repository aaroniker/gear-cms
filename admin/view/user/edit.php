<section id="user">

    <header>

        <h2><?=lang::get('edit'); ?></h2>

        <nav>
            <ul>
                <li>
                    <a href="<?=config::get('url').'admin/user'; ?>" class="button border">
                        <?=lang::get('back'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('username', $model->username);
	    $field->fieldName(lang::get('username'));
        $field->fieldValidate();

        $field = $form->addTextField('email', $model->email);
	    $field->fieldName(lang::get('email'));
        $field->fieldValidate('valid_email|required');

	    $field = $form->addRadioField('status', $model->status);
        $field->fieldName(lang::get('status'));
        $field->add(1, lang::get('active'));
        $field->add(0, lang::get('blocked'));

        if($form->isSubmit()) {

            if($form->validation()) {

			    $edit = $this->model->save($form->getAll());

                if($edit) {
        			echo message::success(lang::get('user_edited'));    
                } else {
        			echo message::success(lang::get('no_data_changed'));
                }

		    } else {
			    echo $form->getErrors();
		    }

	    }

    ?>

    <div class="columns">
        <div class="md-6">

            <?php
                echo $form->show();
            ?>

        </div>

    </div>

</section>
