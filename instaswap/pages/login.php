<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$submitted = true;
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($username !== ''){
		$instapaper = new InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET);
		$token = $instapaper->get_access_token($username, $password);
		if(isset($token['oauth_token']) && isset($token['oauth_token_secret'])){
			// Cool, we’re logging in.
			$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
			$query = $db->prepare('SELECT `id` FROM users WHERE `oauth_token` = ? LIMIT 1');
			if ($query->execute(array($token['oauth_token']))) {
				if ($results = $query->fetch(PDO::FETCH_ASSOC)) {
					$user_id = $results['id'];
				} else {
					$instapaper_user_client = new InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET, $user['oauth_token'], $user['oauth_token_secret']);
					$fuckphp_instapaper_users = $instapaper_user_client->verify_credentials();
					$instapaper_user = $fuckphp_instapaper_users[0];
					$query = $db->prepare('INSERT INTO users (`name`, `instapaper_user`, `oauth_token`, `oauth_token_secret`) VALUES (?, ?, ?)');
					if($query->execute(array($username, $instapaper_user['user_id'], $token['oauth_token'], $token['oauth_token_secret']))){
						if($results = $db->query('SELECT LAST_INSERT_ID()')) {
							$fuck_php = $results->fetch(PDO::FETCH_NUM);
							$user_id = $fuck_php[0];
						}
					}
				}
			}
			if ($user_id != null) {
				session_start();
				$_SESSION['user'] = $user_id;
				header("Location: /", true, 302);
				exit();
			} else {
				$login_failed = true;
			}
			$db = null;
		} else {
			$login_failed = true;
		}
	}
}

?><!DOCTYPE html>
<title>Instaswap &endash; Log in</title>
<link rel="stylesheet" href="style.css">
<h1>Instaswap</h1>
<p>Send links to your friends’ Instapaper accounts.</p><? if ($login_failed): ?>
<p class="error"><span>That username and password isn’t working. Try again?</span></p><? endif; ?>
<p>Log in with Instapaper (I won’t save your password):</p>
<form method="post">
<dl id="login">
	<dt><label for="username">Email address or username</label></dt>
	<dd><input name="username" id="username"<?php if(isset($username)): ?> value="<?= htmlspecialchars($username); ?>"<? endif; ?>></dd>
	<dt><label for="password">Password, if you have one</label></dt>
	<dd><input type="password" name="password" id="password"></dd>
</dl>
<button>Log in</button>
</form>
