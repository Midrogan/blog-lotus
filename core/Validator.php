<?php

namespace core;

use core\Exception\ValidatorException;

class Validator
{
	const typeString = 'string';
	const typeInteger = 'integer';

	public $clean = [];
	public $errors = [];
	public $success = false;
	private $rules;


	public function execute(array $fields)
	{
		if (!$this->rules) {
			throw new ValidatorException("Правила проверки не найдены");
		}

		foreach ($this->rules as $name => $rules) {
			//Обяз
			if (!isset($fields[$name]) && isset($rules['require'])) {
				$this->errors[$name][] = 'Поле обязательно!';
			}

			//Необяз
			if (!isset($fields[$name]) && (!isset($rules['require']) || !$rules['require'])) {
				continue;
			}

			if (isset($rules['not_blank']) && $this->isBlank($fields[$name])) {
				$this->errors[$name][] = 'Поле не может быть пустым!';
			}

			if (isset($rules['type']) && !$this->isTypeMatching($fields[$name], $rules['type'])) {
				$this->errors[$name][] = sprintf('Поле должно иметь тип %s!', $rules['type']);
			}

			if (isset($rules['length']) && !$this->isLenghtMatch($fields[$name], $rules['length'])) {
				$this->errors[$name][] = 'Поле имеет неправильную длину!';
			}



			if (empty($this->errors[$name])) {

				if (isset($rules['type']) && $rules['type'] === self::typeString) {
					$this->clean[$name] = htmlspecialchars(trim($fields[$name]));
				} elseif (isset($rules['type']) && $rules['type'] === self::typeInteger) {
					$this->clean[$name] = (int)$fields[$name];
				} else {
					$this->clean[$name] = $fields[$name];
				}
			}
		}

		if (empty($this->errors)) {
			$this->success = true;
		}
	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	public function isLenghtMatch($fields, $length)
	{
		if ($isArray = is_array($length)) {
			$max = isset($length[1]) ? $length[1] : false;
			$min = isset($length[0]) ? $length[0] : false;
		} else {
			$max = $length;
			$min = false;
		}

		if ($isArray && (!$max || !$min)) {
			throw new ValidatorException("Неверные данные переданы методу isLenghtMatch");
		}

		if ($isArray && !$max) {
			throw new ValidatorException("Неверные данные переданы методу isLenghtMatch");
		}
		$maxIsMatch = $max ? $this->isLenghtMaxMatch($fields, $max) : false;
		$minIsMatch = $min ? $this->isLenghtMinMatch($fields, $min) : false;

		return $isArray ? $maxIsMatch && $minIsMatch : $maxIsMatch;
	}

	public function isLenghtMaxMatch($fields, $length)
	{
		return mb_strlen($fields) > $length === false;
	}

	public function isLenghtMinMatch($fields, $length)
	{
		return mb_strlen($fields) < $length === false;
	}

	public function isTypeMatching($fields, $type)
	{
		switch ($type) {
			case 'string':
				return is_string($fields);
				break;
			case 'int':
			case 'integer':
				return gettype($fields) === self::typeInteger || ctype_digit($fields);
				break;
			default:
				throw new ValidatorException("Неверные данные переданы методу isTypeMatching");
				break;
		}
	}

	public function isBlank($fields)
	{
		$fields = trim($fields);
		return $fields === null || $fields === '';
	}
}
