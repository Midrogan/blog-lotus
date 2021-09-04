<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class BookmarksModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_bookmarks' => [
			'primary' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'bookmarks');
		$this->validator->setRules($this->schema);
	}

	public function checkBookmark($idArticles, $idUser)
	{
		$sql = sprintf('SELECT * FROM %s WHERE id_articles_bookmarks = :idArticles AND id_users_bookmarks = :idUser', $this->table);
		return $this->db->select($sql, ['idArticles' => $idArticles, 'idUser' => $idUser], DBDriver::FETCH_ALL);
	}

	public function getByBookmark($id_users)
	{
		$sql = sprintf(
			'SELECT * FROM bookmarks 
			JOIN articles ON bookmarks.id_articles_bookmarks = articles.id_articles 
			JOIN users ON articles.author_articles = users.name_users
			WHERE id_users_bookmarks = :id_users ORDER BY id_bookmarks DESC'
		);
		return $this->db->select($sql, ['id_users' => $id_users]);
	}

	// public function checkBookmark($idArticles, $idUser)
	// {
	// 	$sql = sprintf('SELECT * FROM %s WHERE id_articles = :idArticles AND id_users = :idUser', $this->table);
	// 	return $this->db->select($sql, ['idArticles' => $idArticles, 'idUser' => $idUser], DBDriver::FETCH_ALL);
	// }

	// public function getByBookmark($id_users)
	// {
	// 	$sql = sprintf('SELECT * FROM %s INNER JOIN articles ON %s.id_articles = articles.id_articles 
	// 	WHERE id_users = :id_users ORDER BY id_%s DESC', $this->table, $this->table, $this->table);
	// 	return $this->db->select($sql, ['id_users' => $id_users]);
	// }
}
