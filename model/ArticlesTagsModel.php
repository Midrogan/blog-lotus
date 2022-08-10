<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class ArticlesTagsModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_tags_articles' => [
			'primary' => true
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'articles_tags');
		$this->validator->setRules($this->schema);
	}

	public function getById($id)
	{
		$sql = sprintf('SELECT * FROM %s WHERE id_%s = :id', $this->table, 'articles');
		return $this->db->select($sql, ['id' => $id]);
	}

	// public function getByTag($id)
	// {
	// 	$sql = sprintf(
	// 		'SELECT * FROM articles_tags
	// 		JOIN articles ON articles_tags.id_articles = articles.id_articles
	// 		JOIN tags ON articles_tags.id_tags = tags.id_tags 
	// 		WHERE tags.id_tags = :id ORDER BY date_articles DESC'
	// 	);
	// 	return $this->db->select($sql, ['id' => $id]);
	// }

	// public function getByLike($id_users)
	// {
	// 	$sql = sprintf(
	// 		'SELECT * FROM likes 
	// 		JOIN articles ON likes.id_articles_likes = articles.id_articles 
	// 		JOIN users ON articles.author_articles = users.name_users
	// 		WHERE id_users_likes = :id_users ORDER BY id_likes DESC'
	// 	);
	// 	return $this->db->select($sql, ['id_users' => $id_users]);
	// }

	public function add(array $params)
	{
		$this->validator->execute($params);
		// var_dump($params);
		// var_dump($this->validator->success);
		if (!$this->validator->success) {
			throw new ModelException($this->validator->errors);
			$this->validator->errors;
		}

		$columns = sprintf('(%s)', implode(', ', array_keys($params)));
		$masks = "({$params['id_articles']}, {$params['id_tags'][0]})";

		if (count($params['id_tags']) > 1) {
			for ($i = 1; $i < count($params['id_tags']); $i++) {

				$masks .= ", ({$params['id_articles']}, {$params['id_tags'][$i]})";
			}
		}

		$sql = sprintf('INSERT INTO %s %s VALUES %s', $this->table, $columns, $masks);

		return $this->db->insertMore($sql);
	}
}
