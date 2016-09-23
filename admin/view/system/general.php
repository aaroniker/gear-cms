<section id="system">

    <header>

        <h2><?=lang::get('general'); ?></h2>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('sitename', option::get('sitename'));
	    $field->fieldName(lang::get('sitename'));
        $field->fieldValidate();

        if($form->isSubmit()) {

            if($form->validation()) {

			    $array = $form->getAll();

                option::set('sitename', $array['sitename']);

                message::success(lang::get('general_edited'));

		    } else {
			    $form->getErrors();
		    }

	    }

    ?>

    <div class="columns">
        <div class="md-9 lg-7">
            <?=$form->show(); ?>
        </div>
    </div>

</section>
