<form action="" method="post">
    <input type="text" name="email">
    <input type="password" name="password">
    <button type="submit">
        Login
    </button>
    <label>
        <input type="checkbox" name="remember" value="1">
        Remember password
    </label>
    <input type="hidden" name="action" value="login">
</form>
<?= $app->view->global('title'); ?>
