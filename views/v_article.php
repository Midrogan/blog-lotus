<div class="two_column">
    <div class="main_column">
        <div class="content">
            <div class="articles_header">
                <div class="articles_meta">
                    <div class="articles_author">
                        Автор:<a class="articles_author_link" href="<?= ROOT ?>profile/<?= $user; ?>"><?= $articles['author_articles']; ?></a>
                    </div>
                    <div class="articles_date"><?= $articles['date_articles']; ?></div>
                </div>
                <h1 class="articles_title"><?= $articles['title_articles']; ?></h1>
            </div>

            <div class="articles_img_box" href="<?= ROOT ?>article/<?= $article['id_articles']; ?>">
                <img class="articles_img" src="<?= $articles['preview_img_articles']; ?>" alt="">
            </div>

            <div class="articles_text"><?= $articles['preview_articles'] ?></div>

            <div class="articles_text"><?= $articles['content_articles'] ?> </div>

            <?php if (isset($tags)) { ?>
                <div class="articles_tags">
                    <?php foreach ($tags as $tag) : ?>
                        <a href="<?= ROOT ?>article/tag/<?= $tag['id_tags']; ?>">
                            <div class="articles_tag">
                                <?= $tag['name_tags']; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php } ?>

            <div class="articles_footer">
                <div class="articles_actions_box">
                    <div class="articles_actions">
                        <div class="articles_action">
                            <img class="articles_icon" src="/media/img/unclickable_eye.png" alt="">
                            <div class="articles_counter_text">Посмотрело</div>
                            <div class="articles_counter"><?= $articles['views_articles'] ?></div>
                        </div>
                        <div class="articles_action">
                            <img class="articles_icon button_like icon_click <?= $articles['check_like']; ?>" src="/media/img/like_inactive.png" data-id="<?= $articles['id_articles']; ?>" alt="">
                            <div class="articles_counter_text">Понравилось</div>
                            <div class="articles_counter "><span class="articles_like"><?= $articles['likes_articles'] ?></span></div>
                        </div>
                        <div class="articles_action">
                            <img class="articles_icon button_bookmark icon_click <?= $articles['check_bookmark']; ?>" src="/media/img/bookmark_inactive.png" data-id="<?= $articles['id_articles']; ?>" alt="">
                            <div class="articles_counter_text">В закладках</div>
                            <div class="articles_counter"><span class="articles_bookmark"><?= $articles['bookmarks_articles'] ?></span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="sidebar_box">
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