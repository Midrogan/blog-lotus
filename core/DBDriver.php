<?php

namespace core;

class DBDriver
{
	const FETCH_ALL = 'all';
	const FETCH_ONE = 'one';

	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function select($sql, array $params = [], $fetch = self::FETCH_ALL)
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		return $fetch === self::FETCH_ALL ? $stmt->fetchAll() : $stmt->fetch();
	}

	public function insert($table, array $params)
	{
		$columns = sprintf('(%s)', implode(', ', array_keys($params)));
		$masks = sprintf('(:%s)', implode(', :', array_keys($params)));

		$sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		return $this->pdo->lastInsertId();
	}

	public function insertMore($sql)
	{
		$this->pdo->query($sql);

		return true;
	}

	public function update($table, $params, $where, $valueWhere)
	{
		$param = [];
		foreach ($params as $key => $value) {
			$param[] = "$key = :$key";
		}

		$columns = sprintf('%s', implode(', ', $param));
		$merge = array_merge($params, $valueWhere);

		$sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $columns, $where);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($merge);

		return true;
	}

	public function delete($table, $where, $valueWhere)
	{
		$sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($valueWhere);

		return true;
	}
}
