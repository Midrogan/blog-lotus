<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class UsersModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_users' => [
			'primary' => true
		],

		'name_users' => [
			'require' => true,
			'not_blank' => true,
			'length' => [4, 50],
			'type' => 'string'
		],

		'password_users' => [
			'require' => true,
			'not_blank' => true,
			'length' => 50,
			// 'length' => [10, 50],
			'type' => 'string'
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'users');
		$this->validator->setRules($this->schema);
	}

	public function add(array $params)
	{
		$this->validator->execute($params);
		// var_dump($this->validator->success);
		if (!$this->validator->success) {
			throw new ModelException($this->validator->errors);
			$this->validator->errors;
		}
		$params['password_users'] = hash('sha512', $params['password_users']);

		return $this->db->insert($this->table, $params);
	}

	public function edit(array $params, $where, array $valueWhere)
	{
		return $this->db->update($this->table, $params, $where, $valueWhere);
	}

	public function deleteAvatar(array $params, $where, array $valueWhere)
	{
		return $this->db->update($this->table, $params, $where, $valueWhere);
	}

	public function checkUser($login, $password)
	{
		$params['name_users'] = $login;
		$params['password_users'] = $password;
		$this->validator->execute($params);
		if (!$this->validator->success) {
			throw new ModelException($this->validator->errors);
			$this->validator->errors;
		}
		$password = hash('sha512', $password);

		$sql = sprintf('SELECT * FROM %s WHERE name_%s = :login AND password_%s = :password', $this->table, $this->table, $this->table);
		return $this->db->select($sql, ['login' => $login, 'password' => $password], DBDriver::FETCH_ALL);
	}

	public function checkLogin($login)
	{
		$sql = sprintf('SELECT * FROM %s WHERE name_%s = :login', $this->table, $this->table);
		return $this->db->select($sql, ['login' => $login], DBDriver::FETCH_ALL);
	}

	public function checkAvatar($avatar)
	{
		$avatar = base64_encode($avatar);
		if ($avatar == '') {
			return '/media/img/default_avatar.png';
		} else {
			return "data:image/jpeg;base64, {$avatar}";
		}
	}
}
