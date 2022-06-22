<?php

namespace controller;

use model\ArticlesModel;
use model\UsersModel;
use model\TagsModel;
use model\ArticlesTagsModel;
use model\LikesModel;
use model\BookmarksModel;
use core\DB;
use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;
use core\Exception\ErrorNotFoundException;


class ArticleController extends BaseController
{
	const roleAdmin = 2;
	const roleModerator = 1;
	const roleUser = 0;

	public function indexAction()
	{
		$this->title = 'Articles | Lotus';

		$mArticles = new ArticlesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$articles = $mArticles->getAll();
		$populars = $mArticles->popularArticles();

		for ($i = 0; $i < count($articles); $i++) {
			$articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
			$articles[$i]['date_articles'] = $mArticles->removeTime($articles[$i]['date_articles']);
		}

		$mTags = new TagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$tags = $mTags->getAll();

		$this->content = $this->build(__DIR__ . '/../views/v_articles.php', ['articles' => $articles, 'populars' => $populars, 'tags' => $tags]);
	}

	// public function pageAction()
	// {
	// 	$this->title = 'Articles | Lotus';
	// 	$id = $this->request->get('id');
	// }

	public function likeAction()
	{
		$sessionId = $this->request->session('userId');

		if (isset($sessionId)) {
			if ($this->request->isPost()) {
				$id = $this->request->post('id');

				$mArticles = new ArticlesModel(
					new DBDriver(DB::connect()),
					new Validator()
				);
				$articles = $mArticles->getById($id);

				$mLikes = new LikesModel(
					new DBDriver(DB::connect()),
					new Validator()
				);
				$likes = $mLikes->checkLike($id, $sessionId);

				if (count($likes) < 1) {
					$mLikes->add([
						'id_articles_likes' => $articles['id_articles'],
						'id_users_likes' => $sessionId,
						'is_likes' => '1'
					]);

					$mArticles->editCount(
						[
							'likes_articles' => $articles['likes_articles'] + 1
						],
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);
				} else {
					$mLikes->delete(
						'id_articles_likes = :id_articles_likes AND id_users_likes = :id_users_likes',
						[
							'id_articles_likes' => $articles['id_articles'],
							'id_users_likes' => $sessionId
						]
					);

					$mArticles->editCount(
						[
							'likes_articles' => $articles['likes_articles'] - 1
						],
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);
				}
			} else {
				$this->redirect(sprintf('/auth/signin'));
			}
		} else {
			$this->redirect(sprintf('/auth/signin'));
		}
	}

	public function bookmarkAction()
	{
		$sessionId = $this->request->session('userId');

		if (isset($sessionId)) {
			if ($this->request->isPost()) {
				$id = $this->request->post('id');

				$mArticles = new ArticlesModel(
					new DBDriver(DB::connect()),
					new Validator()
				);
				$articles = $mArticles->getById($id);

				$mBookmarks = new BookmarksModel(
					new DBDriver(DB::connect()),
					new Validator()
				);
				$bookmark = $mBookmarks->checkBookmark($id, $sessionId);

				if (count($bookmark) < 1) {
					$mBookmarks->add([
						'id_articles_bookmarks' => $articles['id_articles'],
						'id_users_bookmarks' => $sessionId,
						'is_bookmarks' => '1'
					]);

					$mArticles->editCount(
						[
							'bookmarks_articles' => $articles['bookmarks_articles'] + 1
						],
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);
				} else {
					$mBookmarks->delete(
						'id_articles_bookmarks = :id_articles_bookmarks AND id_users_bookmarks = :id_users_bookmarks',
						[
							'id_articles_bookmarks' => $articles['id_articles'],
							'id_users_bookmarks' => $sessionId
						]
					);

					$mArticles->editCount(
						[
							'bookmarks_articles' => $articles['bookmarks_articles'] - 1
						],
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);
				}
			} else {
				$this->redirect(sprintf('/auth/signin'));
			}
		} else {
			$this->redirect(sprintf('/auth/signin'));
		}
	}

	public function oneAction()
	{
		$this->title = 'Article | Lotus';
		$id = $this->request->get('id');
		$sessionId = $this->request->session('userId');

		$mArticles = new ArticlesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$articles = $mArticles->getById($id);
		$populars = $mArticles->popularArticles();

		if ($articles == false) {
			throw new ErrorNotFoundException();
		}

		$cookieName = sprintf('ViewsCookie%s', $id);

		if (!isset($_COOKIE[$cookieName])) {
			$mArticles->editCount(
				[
					'views_articles' => $articles['views_articles'] + 1
				],
				'id_articles = :id_articles',
				[
					'id_articles' => $id
				]
			);
			$articles['views_articles'] += 1;
			setcookie("$cookieName", true, time() + 3600);
		}

		$articles['preview_img_articles'] = $mArticles->checkImg($articles['preview_img_articles']);
		$articles['date_articles'] = $mArticles->removeTime($articles['date_articles']);

		$mUsers = new UsersModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$user = $mUsers->getByName($articles['author_articles']);

		$mLikes = new LikesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$likes = $mLikes->checkLike($id, $sessionId);

		if (count($likes) < 1) {
			$articles['check_like'] = '';
		} else {
			$articles['check_like'] = 'active';
		}

		$mBookmarks = new BookmarksModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$bookmarks = $mBookmarks->checkBookmark($id, $sessionId);

		if (count($bookmarks) < 1) {
			$articles['check_bookmark'] = '';
		} else {
			$articles['check_bookmark'] = 'active';
		}

		$mArticlesTags = new ArticlesTagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$articlesTags = $mArticlesTags->getById($id);

		$mTags = new TagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		// $tags = "";
		$unsortedTags = $mTags->getAll();

		for ($i = 0; $i < count($unsortedTags); $i++) {
			for ($j = 0; $j < count($articlesTags); $j++) {
				if ($unsortedTags[$i]['id_tags'] == $articlesTags[$j]['id_tags']) {
					$tags[$i] = [
						'id_tags' => $unsortedTags[$i]['id_tags'],
						'name_tags' => $unsortedTags[$i]['name_tags'],
					];
					break;
				}
			}
		}
		$this->content = $this->build(__DIR__ . '/../views/v_article.php', ['articles' => $articles, 'populars' => $populars, 'tags' => $tags, 'user' => $user['id_users']]);
	}

	public function tagAction()
	{
		$this->title = 'Articles | Lotus';
		$id = $this->request->get('id');

		$mTags = new TagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$tags = $mTags->getById($id);

		if ($tags == false) {
			throw new ErrorNotFoundException();
		}

		$mArticles = new ArticlesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$articles = $mArticles->getByTag($id);
		$populars = $mArticles->popularArticles();

		for ($i = 0; $i < count($articles); $i++) {
			$articles[$i]['preview_img_articles'] = $mArticles->checkImg($articles[$i]['preview_img_articles']);
			$articles[$i]['date_articles'] = $mArticles->removeTime($articles[$i]['date_articles']);
		}

		$mTags = new TagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);
		$tags = $mTags->getAll();
		$tag = $mTags->getById($id);

		$this->content = $this->build(__DIR__ . '/../views/v_articles_by_tag.php', ['articles' => $articles, 'populars' => $populars, 'tags' => $tags, 'tag' => $tag]);
	}

	public function addAction()
	{
		$this->title = 'Add | Lotus';

		$sessionRole = $this->request->session('userRole');
		if ($sessionRole == self::roleAdmin || $sessionRole == self::roleModerator) {
			$sessionId = $this->request->session('userId');

			$errors['title_articles'][0] = '';
			$errors['preview_img_articles'][0] = '';
			$errors['preview_articles'][0] = '';
			$errors['content_articles'][0] = '';

			$articles['title_articles'] = $this->request->post('title') ?? '';
			$articles['preview_img_articles'] = $this->request->file('previewImg') ?? '';
			$articles['preview_articles'] = $this->request->post('preview') ?? '';
			$articles['content_articles'] = $this->request->post('content') ?? '';

			if ($this->request->isPost()) {

				$mArticles = new ArticlesModel(
					new DBDriver(DB::connect()),
					new Validator()
				);

				$mArticlesTags = new ArticlesTagsModel(
					new DBDriver(DB::connect()),
					new Validator()
				);

				$img = $this->request->file('previewImg');
				if ($img['tmp_name'] !== '') {
					$img = file_get_contents($img['tmp_name']);
				} else {
					$img = '';
				}

				try {

					$id = $mArticles->add([
						'title_articles' => $this->request->post('title'),
						'preview_img_articles' => $img,
						'preview_articles' => $this->request->post('preview'),
						'content_articles' => $this->request->post('content'),
						'author_articles' => $this->request->session('userName')
					]);

					$mArticlesTags->add([
						'id_articles' => $id,
						'id_tags' => $this->request->post('formtag')
					]);

					$this->redirect(sprintf('/article/%s', $id));
				} catch (ModelException $e) {
					// var_dump($e->getErrors());
					//Вывести ошибки в шаблон
					$errors = $e->getErrors();
				}
			}

			$mTags = new TagsModel(
				new DBDriver(DB::connect()),
				new Validator()
			);

			$tags = $mTags->getAll();

			$this->content = $this->build(__DIR__ . '/../views/v_add.php', ['articles' => $articles, 'tags' => $tags, 'errors' => $errors, 'sessionId' => $sessionId]);
		} else {
			$this->redirect(ROOT);
		}
	}

	public function editAction()
	{
		$this->title = 'Edit | Lotus';
		$id = $this->request->get('id');


		$mArticles = new ArticlesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$articles = $mArticles->getById($id);

		if ($articles == false) {
			throw new ErrorNotFoundException();
		}

		$articles['title_articles'] = $this->request->post('title') ?? $articles['title_articles'];
		$articles['preview_articles'] = $this->request->post('preview') ?? $articles['preview_articles'];
		$articles['content_articles'] = $this->request->post('content') ?? $articles['content_articles'];

		$mArticlesTags = new ArticlesTagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$sessionRole = $this->request->session('userRole');
		$sessionName = $this->request->session('userName');
		if ($sessionRole == self::roleAdmin || $sessionRole == self::roleModerator && $sessionName == $articles['author_articles']) {
			$sessionId = $this->request->session('userId');

			$errors['title_articles'][0] = '';
			$errors['preview_articles'][0] = '';
			$errors['content_articles'][0] = '';

			if ($this->request->isPost()) {
				$img = $this->request->file('previewImg');

				try {
					if ($img['tmp_name'] !== '') {
						$img = file_get_contents($img['tmp_name']);
					} else {
						$img = $articles['preview_img_articles'];
					}
					$mArticles->edit(
						[
							'title_articles' => $this->request->post('title'),
							'preview_img_articles' => $img,
							'preview_articles' => $this->request->post('preview'),
							'content_articles' => $this->request->post('content')
						],
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);

					$mArticlesTags->delete(
						'id_articles = :id_articles',
						[
							'id_articles' => $id
						]
					);

					$mArticlesTags->add([
						'id_articles' => $id,
						'id_tags' => $this->request->post('formtag')
					]);

					$this->redirect(sprintf('/article/%s', $id));
				} catch (ModelException $e) {
					// var_dump($e->getErrors());
					//Вывести ошибки в шаблон

					$errors = $e->getErrors();
				}
			}

			$mTags = new TagsModel(
				new DBDriver(DB::connect()),
				new Validator()
			);

			$tags = $mTags->getAll();

			$articlesTags = $mArticlesTags->getAll();

			for ($i = 0; $i < count($tags); $i++) {
				for ($j = 0; $j < count($articlesTags); $j++) {
					if ($tags[$i]['id_tags'] == $articlesTags[$j]['id_tags'] && $articlesTags[$j]['id_articles'] == $id) {
						$tags[$i]['check'] = 'checked';
						break;
					}
				}
			}

			$this->content = $this->build(__DIR__ . '/../views/v_edit.php', ['articles' => $articles, 'tags' => $tags, 'errors' => $errors, 'sessionId' => $sessionId]);
		} else {
			$this->redirect(ROOT);
		}
	}

	public function deleteAction()
	{
		$this->title = 'Delete | Lotus';
		$id = $this->request->get('id');

		$mArticles = new ArticlesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$articles = $mArticles->getById($id);

		if ($articles == false) {
			throw new ErrorNotFoundException();
		}

		$mArticlesTags = new ArticlesTagsModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$mLikes = new LikesModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$mBookmarks = new BookmarksModel(
			new DBDriver(DB::connect()),
			new Validator()
		);

		$sessionRole = $this->request->session('userRole');
		$sessionName = $this->request->session('userName');
		if ($sessionRole == self::roleAdmin || $sessionRole == self::roleModerator && $sessionName == $articles['author_articles']) {

			$mArticlesTags->delete(
				'id_articles = :id_articles',
				[
					'id_articles' => $id
				]
			);

			$mLikes->delete(
				'id_articles_likes = :id_articles_likes',
				[
					'id_articles_likes' => $id
				]
			);

			$mBookmarks->delete(
				'id_articles_bookmarks = :id_articles_bookmarks',
				[
					'id_articles_bookmarks' => $id
				]
			);

			$mArticles->delete(
				'id_articles = :id_articles',
				[
					'id_articles' => $id
				]
			);

			$this->content = $this->build(__DIR__ . '/../views/v_delete.php');
		} else {
			$this->redirect(ROOT);
		}
	}
}
