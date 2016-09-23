<section id="system">

    <header>

        <h2><?=lang::get('advanced'); ?></h2>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('siteurl', config::get('url'));
	    $field->fieldName(lang::get('siteurl'));
        $field->fieldValidate();

        $field = $form->addSwitchField('devStatus', config::get('dev'));
	    $field->fieldName(lang::get('status'));
        $field->add(true, lang::get('development'));

        $field = $form->addSwitchField('cache', config::get('cache'));
	    $field->fieldName(lang::get('cache'));
        $field->add(true, lang::get('yes'));

        if($form->isSubmit()) {

            if($form->validation()) {

			    $array = $form->getAll();

                $dev = ($array['devStatus']) ? true : false;
                $cache = ($array['cache']) ? true : false;

                config::add('url', $array['siteurl'], true);
                config::add('dev', $dev, true);
                config::add('cache', $cache, true);
                config::save();

                message::success(lang::get('advanced_edited'));

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
