<section id="rights">

    <header>

        <h2><?=lang::get('rights'); ?></h2>

    </header>

    <?php

        $form = new form();

        $field = $form->addTextField('name', '');
	    $field->fieldName(lang::get('name'));
        $field->fieldValidate();

    ?>

    <button @click="showModal = true">Show Modal</button>

    <modal :show.sync="showModal">
        <h3 slot="header">custom header</h3>
        <div slot="body">
            <?=$form->show(); ?>
        </div>
    </modal>

    <pre>
        <?php
            var_dump(userPerm::getAll());
        ?>
    </pre>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#rights",
            data: {
                showModal: false
            }
        });
    ');
?>
