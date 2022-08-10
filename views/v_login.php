<div class="auth_box">
    <div class="content">
        <div class="form_header">
            <h1 class="form_title">Авторизация</h1>
        </div>
        <form class="form_box" method="post">
            <div class="form_text">Логин</div>
            <div class="form_error"><?= $errors['name_users'][0]; ?></div>
            <input class="form_input" type="text" name="login" value="<?= $this->request->post('login'); ?>">
            <div class="form_text">Пароль</div>
            <div class="form_error"><?= $errors['password_users'][0]; ?></div>
            <input class="form_input" type="password" name="password" value="<?= $this->request->post('password'); ?>">
            <div class="form_footer">
                <input class="input_button_long" type="submit" value="Войти">
            </div>
        </form>
    </div>
</div>