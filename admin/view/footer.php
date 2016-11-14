</main>

<div id="overlay"></div>

</div>

<?php

    // Components
    include(dir::components('data-table.html'));
    include(dir::components('file-table.html'));
    include(dir::components('modal.html'));
    include(dir::components('searchbox.html'));
    include(dir::components('form-media.html'));
    echo admin::$components;

    $data = lang::loadLang(dir::language(lang::$lang.'.json'));

    echo '
<script>
    var lang = '.json_encode($data).';
    var url = "'.config::get('url').'";
</script>
    ';

?>

<?=theme::getJS(); ?>
<?=theme::getJSCode(); ?>

</body>
</html>
