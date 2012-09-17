<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	switch ($_POST['action']) {
		case 'send':
			$to = $_POST['to'];
			$url = $_POST['url'];
			if ($to && $url){
				if (($user_to = $db->get_user_by_name($to))) {
					if (Instaswap\send($user, $user_to, $url)) {
						$success = 'Sent!';
						// Don’t repopulate the inputs if we’re successful
						$to = null;
						$url = null;
					} else {
						$error = "Failed to send this guy to Instapaper. Darn. Reload to try again.";
					}
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
Instaswap\header();
?>
<form id="sendLink" method="post"><? if ($error): ?>
<p class="message error"><span><?= htmlspecialchars($error) ?></span></p><? elseif ($success): ?>
<p class="message success"><span><?= htmlspecialchars($success) ?></span></p><? endif; ?>
	Send <input name="url"<? if (isset($url)): ?> value="<?= htmlspecialchars($url) ?>"<? endif; ?> class="url" placeholder="http://example.com"> to <input name="to"<? if (isset($to)): ?> value="<?= htmlspecialchars($to) ?>"<? endif; ?> class="recipient" placeholder="sidney">, <button name="action" value="send">please</button>.
</form>
<?
Instaswap\footer();
?>
