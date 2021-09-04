<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

abstract class BaseModel
{
	protected $db;
	protected $table;
	protected $validator;

	public function __construct(DBDriver $db, Validator $validator, $table)
	{
		$this->db = $db;
		$this->table = $table;
		$this->validator = $validator;
	}

	public function getAll()
	{
		$sql = sprintf('SELECT * FROM %s', $this->table);
		return $this->db->select($sql);
	}

	public function getById($id)
	{
		$sql = sprintf('SELECT * FROM %s WHERE id_%s = :id', $this->table, $this->table);
		return $this->db->select($sql, ['id' => $id], DBDriver::FETCH_ONE);
	}

	public function getByName($name)
	{
		$sql = sprintf('SELECT * FROM %s WHERE name_%s = :name', $this->table, $this->table);
		return $this->db->select($sql, ['name' => $name], DBDriver::FETCH_ONE);
	}

	public function add(array $params)
	{
		$this->validator->execute($params);
		// var_dump($this->validator->success);
		if (!$this->validator->success) {
			throw new ModelException($this->validator->errors);
			$this->validator->errors;
		}

		return $this->db->insert($this->table, $params);
	}

	public function edit(array $params, $where, array $valueWhere)
	{
		$this->validator->execute($params);
		if (!$this->validator->success) {
			throw new ModelException($this->validator->errors);
			$this->validator->errors;
		}

		return $this->db->update($this->table, $params, $where, $valueWhere);
	}

	public function delete($where, array $valueWhere)
	{
		return $this->db->delete($this->table, $where, $valueWhere);
	}

	public function removeTime($date)
	{
		return substr(str_replace("-", ".", "$date"), 0, 10);
	}
}
