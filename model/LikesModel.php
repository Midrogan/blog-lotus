<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class LikesModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_likes' => [
			'primary' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'likes');
		$this->validator->setRules($this->schema);
	}

	public function checkLike($idArticles, $idUser)
	{
		$sql = sprintf('SELECT * FROM %s WHERE id_articles_likes = :idArticles AND id_users_likes = :idUser', $this->table);
		return $this->db->select($sql, ['idArticles' => $idArticles, 'idUser' => $idUser], DBDriver::FETCH_ALL);
	}

	public function getByLike($id_users)
	{
		$sql = sprintf(
			'SELECT * FROM likes 
			JOIN articles ON likes.id_articles_likes = articles.id_articles 
			JOIN users ON articles.author_articles = users.name_users
			WHERE id_users_likes = :id_users ORDER BY id_likes DESC'
		);
		return $this->db->select($sql, ['id_users' => $id_users]);
	}

	// public function checkLike($idArticles, $idUser)
	// {
	// 	$sql = sprintf('SELECT * FROM %s WHERE id_articles = :idArticles AND id_users = :idUser', $this->table);
	// 	return $this->db->select($sql, ['idArticles' => $idArticles, 'idUser' => $idUser], DBDriver::FETCH_ALL);
	// }

	// public function getByLike($id_users)
	// {
	// 	$sql = sprintf('SELECT * FROM %s INNER JOIN articles ON %s.id_articles = articles.id_articles 
	// 	WHERE id_users = :id_users ORDER BY id_%s DESC', $this->table, $this->table, $this->table);
	// 	return $this->db->select($sql, ['id_users' => $id_users]);
	// }
}
