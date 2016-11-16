<?php
    admin::addButton('
        <a href="'.url::admin('extensions', ['blocks']).'" class="button border">
            '.lang::get('back').'
        </a>
    ');

    $form = new form();
    $form->setShowSubmit(false);

    $form->addTab(lang::get('info'));
    $form->addRawField('
        <pre>'.json_encode($block->getInfo(), JSON_PRETTY_PRINT).'</pre>
    ');

    $form->addTab(lang::get('content'));
    $form->addRawField('
        <pre>'.htmlentities($block->getContent()).'</pre>
    ');

    $form->addTab(lang::get('css'));
    $form->addRawField('
        <pre>'.$block->getCSS().'</pre>
    ');

?>

<div class="columns">
    <div class="md-9 lg-7">
        <?=$form->show(); ?>
    </div>
</div>

<?php
theme::addJSCode('
    new Vue({
        el: "#app",
        data: {
            headline: "'.$block->getInfo('name').'"
        }
    });
');
?>
