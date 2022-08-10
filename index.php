<?php
// use model\UsersModel;
// use model\ArticlesModel;

// function __autoload($classname)
// {
// 	include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
// }
//ZXC view
spl_autoload_register(function ($classname) {
	include $classname . '.php';
});

session_start();
define('ROOT', '/');
$ROOT = ROOT;

$signout =
	"<img class=\"header_prifile_avatar\" src=\"/media/img/default_avatar.png\" alt=\"\">
<a class=\"header_button\" href=\"{$ROOT}auth/signin\">Войти</a>
<a class=\"header_button\" href=\"{$ROOT}auth/signup\">Регистрация</a>";

if (isset($_SESSION['userId'])) {
	$signout =
		"<a href=\"{$ROOT}auth/signin\"><img class=\"header_prifile_avatar\" src=\"{$_SESSION['userAvatar']}\" alt=\"\"></a>
<a href=\"{$ROOT}profile/like\"><img class=\"header_prifile_img\" src=\"/media/img/like.png\" alt=\"\"></a>
<a href=\"{$ROOT}profile/bookmarks\"><img class=\"header_prifile_img\" src=\"/media/img/bookmark.png\" alt=\"\"></a>
<a href=\"{$ROOT}profile/settings\"><img class=\"header_prifile_img\" src=\"/media/img/settings.png\" alt=\"\"></a>
<a href=\"{$ROOT}auth/signout\"><img class=\"header_prifile_img\" src=\"/media/img/logout.png\" alt=\"\"></a>";
}

$params = explode('/', $_GET['chpu']);
$controller = isset($params[0]) && $params[0] !== '' ? $params[0] : 'article';
try {
	switch ($controller) {
		case 'article':
			$controller = 'Article';
			break;
		case 'auth':
			$controller = 'Auth';
			break;
		case 'profile':
			$controller = 'Profile';
			break;
		default:
			throw new core\Exception\ErrorNotFoundException();
			break;
	}

	$id = false;
	if (isset($params[1]) && is_numeric($params[1])) {
		$id = $params[1];
		$params[1] = 'one';
	}

	$action = isset($params[1]) && $params[1] !== '' && is_string($params[1]) ? $params[1] : 'index';
	$action = sprintf('%sAction', $action);

	if (!$id) {
		$id = isset($params[2]) && is_numeric($params[2]) ? $params[2] : false;
	}

	if ($id) {
		$_GET['id'] = $id;
	}

	$request = new core\Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);

	$script = '';

	if (isset($_SESSION['userId'])) {
		$script =
			'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
  	<script src="/media/js/like.js" type="text/javascript"></script>';
	}

	$controller = sprintf('controller\%sController', $controller);

	$controller = new $controller($request, $signout, $script);
	$controller->$action();
} catch (\Exception $e) {
	$controller = sprintf('controller\%sController', 'Base');
	$controller = new $controller($request, $signout, $script);
	$controller->errorHandler($e->getMessage(), $e->getTraceAsString());
}

$controller->render();
