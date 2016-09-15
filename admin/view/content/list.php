<section id="content">

    <header>

        <h2>{{{ headline | lang }}}</h2>

        <nav>
            <ul>
                <li>
                    <a href="<?=url::admin('content', ['index', 'add']); ?>" class="button">
                        <?=lang::get('add'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#content",
        data: {
            headline: "pages"
        }
    });
');
?>
