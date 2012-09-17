<?php
namespace Instaswap;

define(__NAMESPACE__ . '\BOOKMARKLET', <<<EOF
javascript:(function(){var e=document,t=e.body,n,r=encodeURIComponent,i="createElement",s="appendChild",o="documentElement",u=prompt("Instaswap\\n\\nSend this page to\u2026");if(u){if(!t){t=e[i]("body");if(!e[o])return;e[o][s](t)}n=e[i]("script"),n.setAttribute("src","http://instaswap.sidnicio.us/api?to="+r(u)+"&url="+r(e.location.href)),t[s](n)}})()
EOF
);

function header(){
	global $user;
?>
<!DOCTYPE html>
<title>Instaswap &ndash; Home</title>
<link rel="stylesheet" href="style.css">
<ul id="nav">
	<li><?= htmlspecialchars($user['name']) ?></li><!--
	--><li>Bookmarklet: <a onclick="alert('Add this link to your bookmarks and it’ll work super great!');return false" href="<?= htmlspecialchars(BOOKMARKLET) ?>">Instaswap</a></li><!--
	--><li><a href="/settings">Settings</a></li><!--
	--><li><form action="/logout" method="post" style="display: inline"><button class="link">Log out</button></form></li>
</ul>
<h1><a href="/" class="whatLink">Instaswap</a></h1>
<?
}

function footer(){?>
<p id="footer">
	Questions? Email me: <a href="hello+instaswap@sidneysm.com">hello+instaswap@sidneysm.com</a>
</p>
<?}

function send($user_from, $user_to, $url){
	$instapaper_user_client = new \InstapaperOAuth(INSTAPAPER_CONSUMER_KEY, INSTAPAPER_CONSUMER_SECRET, $user_to['oauth_token'], $user_to['oauth_token_secret']);
	$bookmark = $instapaper_user_client->add_bookmark($url, array(
		'resolve_final_url' => 0,
		'description' => "Sent by ${user_from["name"]} through Instaswap"
	));
	// // TODO: Remove this!
	// echo '<!--';
	// var_dump($bookmark);
	// echo '-->';
	return true;
}
?>
