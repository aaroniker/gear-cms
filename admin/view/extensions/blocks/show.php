<section id="block">

    <header>

        <h2><?=$block->getInfo('name'); ?></h2>

        <nav>
            <ul>
                <li>
                    <a href="<?=url::admin('extensions', ['blocks']); ?>" class="button border">
                        <?=lang::get('back'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <?php

        $form = new form();
        $form->setShowSubmit(false);

        $form->addTab(lang::get('info'));
        $form->addRawField('
            <pre><code>'.json_encode($block->getInfo(), JSON_PRETTY_PRINT).'</code></pre>
        ');

        $form->addTab(lang::get('content'));
        $form->addRawField('
            <pre><code>'.htmlentities($block->getContent()).'</code></pre>
        ');

        $form->addTab(lang::get('css'));
        $form->addRawField('
            <pre><code>'.$block->getCSS().'</code></pre>
        ');

    ?>

    <div class="columns">
        <div class="md-9 lg-7">
            <?=$form->show(); ?>
        </div>
    </div>

</section>
