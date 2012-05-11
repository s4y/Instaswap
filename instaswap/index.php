<?php
require_once("InstapaperOAuth/InstapaperOAuth.php");
require_once('config.php');
require_once('db.php');

function destroy_session(){
	if (isset($_COOKIE[session_name()])) {
		session_destroy();
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
		    $params["path"], $params["domain"],
		    $params["secure"], $params["httponly"]
		);
	}
}

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_name('auth');
if (isset($_COOKIE[session_name()])) {
	session_start();
}

$db = new InstaswapDB();

switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
	case '/':
		if (!$_SESSION['user']) {
			header("Location: /login", true, 303);
		} else {
			require('pages/home.php');
		}
		break;
	case '/settings':
		if (!$_SESSION['user']) {
			header("Location: /login", true, 303);
		} else {
			require('pages/settings.php');
		}
		break;
	case '/login':
		if ($_SESSION['user']) {
			header("Location: /", true, 303);
		} else {
			require('pages/login.php');
		}
		break;
	case '/logout':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			destroy_session();
			header("Location: /login", true, 303);
		} else {
			header("HTTP/1.0 405 Method Not Allowed");
			require('404.php');
		}
		break;
	default:
		header("HTTP/1.0 404 Not Found");
		require('404.php');
		break;
}

?>