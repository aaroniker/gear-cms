
    </main>
</div>

<div id="overlay"></div>

<?php

    $data = lang::loadLang(dir::language(lang::$lang.'.json'));

    echo '
        <script>
            var lang = '.json_encode($data).';
        </script>
    ';

?>

<?=theme::getJS(); ?>
<?=theme::getJSCode(); ?>

</body>
</html>
