<div class="main_column">
    <div class="content">
        <div class="profile_header">
            <img class="profile_avatar" src="<?= $avatar ?>" alt="">
            <div class="profile_name"><?= $user['name_users'] ?></div>
        </div>
        <div class="profile_meta">
            <div class="profile_info"><?= $user['info_users'] ?></div>
            <div class="profile_date">Зарегистрирован с <?= $user['date_users'] ?></div>
        </div>
    </div>
    <div class="content">
        <div class="form_header">
            <h1 class="form_title">Настройка профиля</h1>
        </div>

        <form class="form_box" method="post" enctype="multipart/form-data">
            <div class="form_text">Аватар</div>
            <div class="form_file">
                <img class="form_avatar" src="<?= $avatar ?>" alt="">
                <input class="form_input_file" type="file" id="checkbox" name="avatar" value="">
                <label class="unselectable" for="checkbox">Изменить фото</label>
                <a href="<?= ROOT ?>profile/deleteAvatar"><img class="form_delete_avatar" src="/media/img/delete_avatar.png" alt=""></a>
            </div>
            <div class="form_text">О себе</div>
            <textarea class="form_input" name="info"><?= $user['info_users']; ?></textarea>
            <div class="form_footer">
                <input class="input_button_confirm" type="submit" value="Сохранить">
                <a class="articles_button_delete" href="<?= ROOT ?>profile/<?= $user['id_users'] ?>" type="button">Отмена</a>
            </div>
        </form>
    </div>
</div>