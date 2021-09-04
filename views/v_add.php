<div class="main_column">
    <div class="content">
        <div class="form_header">
            <h1 class="form_title">Создание статьи</h1>
        </div>
        <form class="form_box" method="post" enctype="multipart/form-data">
            <div class="form_text">Заголовок</div>
            <div class="form_error"><?= $errors['title_articles'][0]; ?></div>
            <input class="form_input" type="text" name="title" value="<?= $articles['title_articles']; ?>">
            <div class="form_text">Превью</div>
            <div class="form_error"><?= $errors['preview_articles'][0]; ?></div>
            <textarea class="form_textarea_preview" name="preview"><?= $articles['preview_articles']; ?></textarea>
            <div class="form_text">Текст</div>
            <div class="form_error"><?= $errors['content_articles'][0]; ?></div>
            <textarea class="form_textarea_content" name="content"><?= $articles['content_articles']; ?></textarea>
            <div class="form_text">Фото для превью</div>
            <div class="form_error"><?= $errors['preview_img_articles'][0]; ?></div>
            <input class="form_input_long_file" type="file" id="checkbox" name="previewImg" value="">
            <label class="unselectable" for="checkbox">Добавить фото</label>
            <div class="form_text">Категории</div>
            <div class="form_tags">
                <?php foreach ($tags as $tag) : ?>
                    <input class="checkbox_tags" type="checkbox" id="checkbox<?= $tag['id_tags']; ?>" name="formtag[]" value="<?= $tag['id_tags']; ?>">
                    <label class="unselectable" for="checkbox<?= $tag['id_tags']; ?>"><?= $tag['name_tags']; ?></label>
                <?php endforeach; ?>
            </div>
            <div class="form_footer">
                <input class="input_button_confirm" type="submit" value="Добавить">
                <a class="articles_button_delete" href="<?= ROOT ?>profile/<?= $sessionId ?>" type="button">Отмена</a>
            </div>
        </form>
    </div>
</div>