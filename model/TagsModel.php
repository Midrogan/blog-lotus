<?php

namespace model;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class TagsModel extends BaseModel
{
	protected $validator;
	protected $schema = [
		'id_tags' => [
			'primary' => true
		],

		'name_tags' => [
			'require' => true,
			'not_blank' => true,
			'length' => 50,
			'type' => 'string'
		]
	];

	public function __construct(DBDriver $db, Validator $validator)
	{
		parent::__construct($db, $validator, 'tags');
		$this->validator->setRules($this->schema);
	}
}
