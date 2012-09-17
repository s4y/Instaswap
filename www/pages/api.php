<?php

// Return functions. Ends execution.
function rtn($script)
{
	@header("Content-Type: application/x-javascript");
	echo $script;
	exit;
}
function rtnScript($script)
{
	rtn(<<<EOF
(function(){
	var scripts = document.getElementsByTagName('script'),
	    myScript = scripts[scripts.length-1];
	$script
	document.body.removeChild(myScript);
})()
EOF
);
}
function rtnAlert($text)
{
	rtnScript("alert(\"".str_replace('"', '\"', str_replace('\\', '\\\\', $text))."\");");
}

header("Cache-Control: no-cache");
header("Expires: -1");


if (!isset($user)) {
	// TODO: Offer to pop Instaswap up in a new window
	rtnAlert("Hey, you’re not logged in to Instaswap!");
}
$to = $_GET['to'];
$url = $_GET['url'];
if ($to && $url){
	if (($user_to = $db->get_user_by_name($to))) {
		if (Instaswap\send($user, $user_to, $url)) {
			rtnAlert("Success! Sent to ${to}.");
		} else {
			// TODO: Really? This is quite a shitty error
			// Also, logging would be good
			rtnAlert("Darn. Something went wrong. You might want to try again.");
		}
	} else {
		rtnAlert("I'm not sure who $to is.");
	}
}

?>
<!DOCTYPE html>
<link rel="stylesheet" href="style.css">
Oh, hi there! Were you looking for <a href="/">Instaswap</a>?
