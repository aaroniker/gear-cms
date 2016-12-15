
<?php

    $data = lang::loadLang(dir::language(lang::$lang.'.json'));

    echo '
<script>
    var lang = '.json_encode($data).';
    var url = "../";
</script>
    ';

?>

<?=theme::getJS(); ?>
<?=theme::getJSCode(); ?>

</body>
</html>
