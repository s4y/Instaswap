<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$submitted = true;
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($username !== ''){
		$instapaper = new InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET);
		$token = $instapaper->get_access_token($username, $password);
		if(isset($token['oauth_token']) && isset($token['oauth_token_secret'])){
			// Cool, we’re logging in. Let’s see if we already know about a user with this token.
			if (is_null($user_id = $db->user_id_by_token($token['oauth_token']))) {
				// Let’s find out this user’s Instapaper user ID and go from there.
				$instapaper_user_client = new InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET, $token['oauth_token'], $token['oauth_token_secret']);
				$user_credentials_fuck = $instapaper_user_client->verify_credentials();
				$user_credentials = $user_credentials_fuck[0];
				if ( ! is_null($user_credentials->user_id)) {
					// Got it. Do we know about this user, but its OAuth token has changed?
					if (is_null($user_id = $db->user_id_by_instapaper_id($user_credentials->user_id))) {
						// Nope, we’ve got a new user!
						$user_id = $db->create_user($username, $user_credentials->user_id, $token['oauth_token'], $token['oauth_token_secret']);
					} else {
						// This may only every happen if Instapaper someday provides a way to
						// revoke apps’ access, and a user decides to try Instaswap again after
						// revoking it.
						// TODO: log this
						$db->update_user_token($user_id, $token['oauth_token'], $token['oauth_token_secret']);
					}
				}
			}
			if ($user_id != null) {
				session_start();
				$_SESSION['user'] = $user_id;
				header("Location: /", true, 303);
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
<title>Instaswap &ndash; Log in</title>
<link rel="stylesheet" href="style.css">
<h1>Instaswap</h1>
<p>Send links to your friends’ Instapaper accounts.</p><? if ($login_failed): ?>
<p class="message error"><span>That username and password isn’t working. Try again?</span></p><? endif; ?>
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