<?php
$user = $db->get_user_by_id($_SESSION['user']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	switch ($_POST['action']) {
		case 'send':
			$to = $_POST['to'];
			$url = $_POST['url'];
			if ($to && $url){
				if ($user_id_to = $db->user_id_by_name($to) && $user_to = $db->get_user_by_id($user_id_to)) {
					$instapaper_user_client = new InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET, $user_to['oauth_token'], $user_to['oauth_token_secret']);
					$instapaper_user_client->add_bookmark($url, array(
						'resolve_final_url' => 0,
						'description' => "Sent by ${user["name"]} through Instaswap"
					));
					$success = 'Sent!';
					$to = null;
					$url = null;
				} else {
					$error = "I couldn’t find anyone named “${to}”";
				}
			} else {
				$error = "Did you forget something?";
			}
			break;
		default:
			echo 'huh: ' . $_POST['action'];
	}
}
?><!DOCTYPE html>
<title>Instaswap &ndash; Home</title>
<link rel="stylesheet" href="style.css">
<ul id="nav">
	<li>s@sidneysm.com</li><!--
	--><li><a href="/settings">Settings</a></li><!--
	--><li><form action="/logout" method="post" style="display: inline"><button class="link">Log out</button></form></li>
</ul>
<h1><a href="/" class="whatLink">Instaswap</a></h1>
<form id="sendLink" method="post"><? if ($error): ?>
<p class="message error"><span><?= htmlspecialchars($error) ?></span></p><? elseif ($success): ?>
<p class="message success"><span><?= htmlspecialchars($success) ?></span></p><? endif; ?>
	Send <input name="url"<? if (isset($url)): ?> value="<?= htmlspecialchars($url) ?>"<? endif; ?> class="url" placeholder="http://example.com"> to <input name="to"<? if (isset($to)): ?> value="<?= htmlspecialchars($to) ?>"<? endif; ?> class="recipient" placeholder="sidney">, <button name="action" value="send">please</button>.
</form>