<?php

namespace model;

use core\DBDriver;
use core\Validator;

class ArticlesModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_articles' => [
			'primary' => true
		],

		'title_articles' => [
			'require' => true,
			'not_blank' => true,
			'length' => [2, 50],
			'type' => 'string'
		],

		'preview_img_articles' => [
			// 'require' => true,
			'not_blank' => true
		],

		'preview_articles' => [
			'require' => true,
			'not_blank' => true,
			'length' => 1000,
			'type' => 'string'
		],

		'content_articles' => [
			'require' => true,
			'not_blank' => true,
			'length' => 8000,
			'type' => 'string'
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'articles');
		$this->validator->setRules($this->schema);
	}

	// public function getAll()
	// {
	// 	$sql = sprintf('SELECT * FROM %s ORDER BY date_%s DESC', $this->table, $this->table);
	// 	return $this->db->select($sql);
	// }

	public function getAll()
	{
		$sql = sprintf(
			'SELECT %1$s.id_%1$s, %1$s.title_%1$s, %1$s.preview_img_%1$s, %1$s.preview_%1$s, %1$s.content_%1$s, %1$s.author_%1$s, 
			%1$s.date_%1$s, views_%1$s, %1$s.likes_%1$s, %1$s.bookmarks_%1$s, users.id_users 
			FROM %1$s 
			INNER JOIN users ON %1$s.author_%1$s = users.name_users 
			ORDER BY date_%1$s DESC',
			$this->table
		);
		return $this->db->select($sql);
	}

	public function getByName($name)
	{
		$sql = sprintf(
			'SELECT * FROM %s WHERE author_%s = :name ORDER BY date_%s DESC',
			$this->table,
			$this->table,
			$this->table
		);
		return $this->db->select($sql, ['name' => $name]);
	}

	// public function getByName($name)
	// {
	// 	$sql = sprintf(
	// 		'SELECT %1$s.id_%1$s, %1$s.title_%1$s, %1$s.preview_%1$s, %1$s.content_%1$s, %1$s.author_%1$s, 
	// 		%1$s.date_%1$s, views_%1$s, %1$s.likes_%1$s, %1$s.bookmarks_%1$s, users.id_users 
	// 		FROM %1$s
	// 		INNER JOIN users ON %1$s.author_%1$s = users.name_users  
	// 		WHERE %1$s.author_%1$s = :name 
	// 		ORDER BY %1$s.date_%1$s DESC',
	// 		$this->table
	// 	);
	// 	return $this->db->select($sql, ['name' => $name]);
	// }

	public function getByTag($id)
	{
		$sql = sprintf(
			'SELECT * FROM articles 
			JOIN articles_tags ON articles.id_articles = articles_tags.id_articles
			JOIN tags ON articles_tags.id_tags = tags.id_tags 
			JOIN users ON articles.author_articles = users.name_users
			WHERE tags.id_tags = :id ORDER BY date_articles DESC'
		);
		return $this->db->select($sql, ['id' => $id]);
	}

	public function popularArticles()
	{
		$sql = sprintf('SELECT * FROM %s ORDER BY views_%s DESC limit 6', $this->table, $this->table);
		return $this->db->select($sql);
	}

	public function checkImg($img)
	{
		$img = base64_encode($img);
		if ($img == '') {
			return '/media/img/default_preview_img.jpg';
		} else {
			return "data:image/jpeg;base64, {$img}";
		}
	}

	public function editCount(array $params, $where, array $valueWhere)
	{
		return $this->db->update($this->table, $params, $where, $valueWhere);
	}
}
