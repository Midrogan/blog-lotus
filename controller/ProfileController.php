<?php

namespace controller;

use model\UsersModel;
use model\ArticlesModel;
use model\LikesModel;
use model\BookmarksModel;
use core\DB;
use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;
use core\Exception\ErrorNotFoundException;

class ProfileController extends BaseController
{
    const roleAdmin = 2;
    const roleModerator = 1;
    const roleUser = 0;

    public function oneAction()
    {
        $id = $this->request->get('id');

        $mUsers = new UsersModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $user = $mUsers->getByid($id);

        if ($user == false) {
            throw new ErrorNotFoundException();
        }

        $user['date_users'] = $mUsers->removeTime($user['date_users']);
        $avatar = $mUsers->checkAvatar($user['avatar_users']);

        $mArticles = new ArticlesModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $sessionId = $this->request->session('userId');

        if ($sessionId == $id) {
            $name = $this->request->session('userName');
            $this->title = "$name | Lotus";

            if ($user['role_users'] == self::roleAdmin) {
                $articles = $mArticles->getAll();
                for ($i = 0; $i < count($articles); $i++) {
                    $articles[$i]['date_articles'] = $mArticles->removeTime($articles[$i]['date_articles']);
                    $articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
                }

                $this->content = $this->build(__DIR__ . '/../views/v_admin_profile.php', ['articles' => $articles, 'user' => $user, 'avatar' => $avatar]);
            } elseif ($user['role_users'] == self::roleModerator) {
                $articles = $mArticles->getByName($name);
                for ($i = 0; $i < count($articles); $i++) {
                    $articles[$i]['date_articles'] = $mArticles->removeTime($articles[$i]['date_articles']);
                    $articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
                }

                $this->content = $this->build(__DIR__ . '/../views/v_moderator_profile.php', ['articles' => $articles, 'user' => $user, 'avatar' => $avatar]);
            } else {

                $this->content = $this->build(__DIR__ . '/../views/v_user_profile.php', ['user' => $user, 'avatar' => $avatar]);
            }
        } else {
            $name = $user['name_users'];
            $this->title = "$name | Lotus";
            $articles = $mArticles->getByName($user['name_users']);
            for ($i = 0; $i < count($articles); $i++) {
                $articles[$i]['date_articles'] = $mArticles->removeTime($articles[$i]['date_articles']);
                $articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
            }

            $this->content = $this->build(__DIR__ . '/../views/v_showing_profile.php', ['articles' => $articles, 'user' => $user, 'avatar' => $avatar]);
        }
    }

    public function likeAction()
    {
        $this->title = "Like | Lotus";

        $sessionId = $this->request->session('userId');

        $mArticles = new ArticlesModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $mLikes = new LikesModel(
            new DBDriver(DB::connect()),
            new Validator()
        );
        $articles = $mLikes->getByLike($sessionId);
        for ($i = 0; $i < count($articles); $i++) {
            $articles[$i]['date_articles'] = $mLikes->removeTime($articles[$i]['date_articles']);
            $articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
        }

        $this->content = $this->build(__DIR__ . '/../views/v_like.php', ['articles' => $articles]);
    }

    public function bookmarksAction()
    {
        $this->title = "Bookmarks | Lotus";

        $sessionId = $this->request->session('userId');

        $mArticles = new ArticlesModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $mBookmarks = new BookmarksModel(
            new DBDriver(DB::connect()),
            new Validator()
        );
        $articles = $mBookmarks->getByBookmark($sessionId);
        for ($i = 0; $i < count($articles); $i++) {
            $articles[$i]['date_articles'] = $mBookmarks->removeTime($articles[$i]['date_articles']);
            $articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
        }

        $this->content = $this->build(__DIR__ . '/../views/v_bookmarks.php', ['articles' => $articles]);
    }

    public function settingsAction()
    {
        $this->title = "Settings | Lotus";

        $sessionId = $this->request->session('userId');

        $mUsers = new UsersModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $user = $mUsers->getByid($sessionId);

        $user['date_users'] = $mUsers->removeTime($user['date_users']);
        $avatar = $mUsers->checkAvatar($user['avatar_users']);

        if ($this->request->isPost()) {
            $img = $this->request->file('avatar');

            if ($img['tmp_name'] !== '') {
                $img = file_get_contents($img['tmp_name']);

                $mUsers->edit(
                    [
                        'avatar_users' => $img
                    ],
                    'id_users = :id_users',
                    [
                        'id_users' => $sessionId
                    ]
                );

                $_SESSION['userAvatar'] = $mUsers->checkAvatar($img);
            }

            $mUsers->edit(
                [
                    'info_users' => $this->request->post('info')
                ],
                'id_users = :id_users',
                [
                    'id_users' => $sessionId
                ]
            );

            $this->redirect(sprintf('/profile/settings'));
        }

        $this->content = $this->build(__DIR__ . '/../views/v_settings.php', ['user' => $user, 'avatar' => $avatar]);
    }

    public function deleteAvatarAction()
    {
        $sessionId = $this->request->session('userId');

        $mUsers = new UsersModel(
            new DBDriver(DB::connect()),
            new Validator()
        );

        $mUsers->deleteAvatar(
            [
                'avatar_users' => ''
            ],
            'id_users = :id_users',
            [
                'id_users' => $sessionId
            ]
        );

        $_SESSION['userAvatar'] = $mUsers->checkAvatar('');

        $this->redirect(sprintf('/profile/settings'));
    }
}
