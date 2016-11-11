<section id="system">

    <header>

        <h2><?=lang::get('settings'); ?></h2>

    </header>

    <?php

        $form = new form();

        $form->addTab(lang::get('general'));

        $field = $form->addTextField('sitename', option::get('sitename'));
        $field->fieldName(lang::get('sitename'));
        $field->fieldValidate();

        $form->addTab(lang::get('advanced'));

        $field = $form->addTextField('siteurl', config::get('url'));
        $field->fieldName(lang::get('siteurl'));
        $field->fieldValidate();

        $field = $form->addSwitchField('cache', config::get('cache'));
        $field->fieldName(lang::get('cache'));
        $field->add(true, lang::get('yes'));

        if($form->isSubmit()) {

            if($form->validation()) {

                $array = $form->getAll();

                $cache = ($array['cache']) ? true : false;

                config::add('url', $array['siteurl'], true);
                config::add('cache', $cache, true);
                config::save();

                option::set('sitename', $array['sitename']);

                message::success(lang::get('settings_edited'));

                url::refresh();

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
