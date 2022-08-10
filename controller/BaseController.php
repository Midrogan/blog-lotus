<?php

namespace controller;

use core\Request;
use core\Exception\ErrorNotFoundException;

class BaseController
{
	protected $title;
	protected $content;
	protected $request;
	protected $signout;

	public function __construct(Request $request = null, $signout, $script)
	{
		$this->request = $request;
		$this->signout = $signout;
		$this->script = $script;
		$this->title = 'Lotus';
		$this->content = '';
	}

	public function render()
	{
		echo $this->build(
			__DIR__ . '/../views/v_main.php',
			[
				'title' => $this->title,
				'content' => $this->content,
				'signout' => $this->signout,
				'script' => $this->script
			]
		);
	}

	public function __call($name, $arguments)
	{
		throw new ErrorNotFoundException();
	}

	public function errorHandler($massage, $trace)
	{
		$this->content = $massage;
	}

	protected function redirect($uri)
	{
		header(sprintf('Location: %s', $uri));
		die();
	}

	protected function build($template, array $params = [])
	{
		ob_start();
		extract($params);
		include_once $template;

		return ob_get_clean();
	}
}
