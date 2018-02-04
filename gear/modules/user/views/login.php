<form action="" method="post">
    <input type="text" name="email">
    <input type="password" name="password">
    <button type="submit">
        <?= __('Login'); ?>
    </button>
    <label>
        <input type="checkbox" name="remember" value="1">
        <?= __('Stay logged in'); ?>
    </label>
    <input type="hidden" name="action" value="login">
</form>
