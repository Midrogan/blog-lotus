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
        <div class="articles_add">
            <a class="articles_button" href="<?= ROOT ?>article/add">Создать</a>

            <h1 class="title_add">Написать статью</h1>
            <div></div>
        </div>
    </div>
    <?php foreach ($articles as $article) : ?>
        <div class="content">
            <div class="articles_header">
                <div class="articles_meta">
                    <div class="articles_author">
                        Автор:<a class="articles_author_link" href="<?= ROOT ?>profile/<?= $user['id_users']; ?>"><?= $article['author_articles']; ?></a>
                    </div>
                    <div class="articles_date"><?= $article['date_articles']; ?></div>
                </div>
                <h1 class="articles_title"><a class="articles_title_link" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>"><?= $article['title_articles']; ?></a></h1>
            </div>

            <a class="articles_img_box" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>">
                <img class="articles_img" src="<?= $article['preview_img_articles']; ?>" alt=""></a>

            <div class="articles_text"><?= $article['preview_articles']; ?></div>
            <div class="articles_footer">
                <div class="articles_button_box">
                    <a class="articles_button" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>">Читать</a>
                    <a class="articles_button" href="<?= ROOT ?>article/edit/<?= $article['id_articles']; ?>">Редактировать</a>
                    <a class="articles_button_delete" href="<?= ROOT ?>article/delete/<?= $article['id_articles']; ?>">Удалить</a>
                </div>
                <div class="articles_actions">
                    <div class="articles_action">
                        <img class="articles_icon" src="/media/img/unclickable_eye.png" alt="">
                        <div class="articles_counter"><?= $article['views_articles'] ?></div>
                    </div>
                    <div class="articles_action">
                        <img class="articles_icon" src="/media/img/unclickable_like.png" alt="">
                        <div class="articles_counter"><?= $article['likes_articles'] ?></div>
                    </div>
                    <div class="articles_action">
                        <img class="articles_icon" src="/media/img/unclickable_bookmark.png" alt="">
                        <div class="articles_counter"><?= $article['bookmarks_articles'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>