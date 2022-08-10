<div class="two_column">
    <div class="main_column">
        <div class="content">
            <div class="main_title_box">
                <h1 class="main_title"><?= $tag['name_tags']; ?></h1>
            </div>
        </div>
        <?php foreach ($articles as $article) : ?>
            <div class="content">
                <div class="articles_header">
                    <div class="articles_meta">
                        <div class="articles_author">
                            Автор:<a class="articles_author_link" href="<?= ROOT ?>profile/<?= $article['id_users']; ?>"><?= $article['author_articles']; ?></a>
                        </div>
                        <div class="articles_date"><?= $article['date_articles']; ?></div>
                    </div>

                    <h1 class="articles_title"><a class="articles_title_link" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>"><?= $article['title_articles']; ?></a></h1>

                </div>
                <a class="articles_img_box" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>">
                    <img class="articles_img" src="<?= $article['preview_img_articles']; ?>" alt=""></a>

                <div class="articles_text"><?= $article['preview_articles']; ?></div>

                <div class="articles_footer">
                    <a class="articles_button" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>">Читать</a>
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
                        <!-- <div class="articles_action">
                            <img class="articles_icon" src="/media/img/comments.png" alt="">
                            <div class="articles_counter">0</div>
                        </div> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="sidebar_box">
        <div class="sidebar_tags">
            <h1 class="sidebar_title">Категории</h1>
            <div class="sidebar_items">
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?= ROOT ?>article/tag/<?= $tag['id_tags']; ?>">
                        <div class="articles_tag">
                            <?= $tag['name_tags']; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sidebar_recommendation">
            <h1 class="sidebar_title">
                <!--Популярные статьи--> Рекомендуем прочитать
            </h1>
            <div class="popular_articles_box">
                <?php foreach ($populars as $popular) : ?>
                    <div class="popular_articles">
                        <h1 class="popular_articles_title"><a class="articles_title_link" href="<?= ROOT ?>article/<?= $popular['id_articles']; ?>"><?= $popular['title_articles']; ?></a></h1>
                        <div class="sidebar_footer">
                            <div class="articles_action">
                                <img class="articles_icon" src="/media/img/unclickable_eye.png" alt="">
                                <div class="articles_counter"><?= $popular['views_articles'] ?></div>
                            </div>
                            <div class="articles_action">
                                <img class="articles_icon" src="/media/img/unclickable_like.png" alt="">
                                <div class="articles_counter"><?= $popular['likes_articles'] ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>