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
	rtnScript("alert(\"".str_replace('"', '\"', $text)."\");");
}

function instapaperAdd($username, $password, $params){
	$request = curl_init('https://www.instapaper.com/api/add');
	//curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
	//curl_setopt($request, CURLOPT_HEADER, 1);
	curl_setopt($request, CURLOPT_USERPWD, $username . ":" . $password);
	curl_setopt($request, CURLOPT_TIMEOUT, 30);
	curl_setopt($request, CURLOPT_POST, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $params);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
	$return = curl_exec($request);
	$status = curl_getinfo($request, CURLINFO_HTTP_CODE);
	
	return $status > 200 && $status < 299;
}

$accounts = array(
	array('name' => 'steve', 'email' => 'steve@example.com', 'instapaperPassword' => 'cGFzc3dvcmQ=', 'apiKey' => '7A945BB8-53F0-4FC9-8EEF-6D86F168A8D2')
);

header("Content-Type: text/plain");
header("Cache-Control: no-cache");
header("Expires: -1");

$key = (array_key_exists('key', $_GET)) ? $_GET['key'] : null;
$target = (array_key_exists('target', $_GET)) ? $_GET['target'] : null;
$url = (array_key_exists('url', $_GET)) ? $_GET['url'] : null;

if ($key && $target && $url) {
	foreach ($accounts as $searchAccount) {
		if ($searchAccount['apiKey'] === $key) {
			$account = $searchAccount;
		}
	}
	foreach ($accounts as $searchAccount) {
		if ($searchAccount['name'] === $target) {
			$targetAccount = $searchAccount;
		}
	}
}
if (!isset($account)) {
//	header("HTTP/1.1 403 Forbidden");
	rtnalert('403 Forbidden');
	exit;
}
if (!isset($targetAccount)) {
//	header("HTTP/1.1 404 Not Found");
	rtnalert('I\'m not sure who that is.');
	exit;
}

// if ($account === $targetAccount) {
// 	header("HTTP/1.1 403 Forbidden");
// 	echo 'You don\'t want to do that.';
// 	exit;
// }

if (instapaperAdd($targetAccount['email'], base64_decode($targetAccount['instapaperPassword']), array('url' => $url, 'selection' => 'Sent by '.$account['email'].' through InstaSwap'))) {
	rtnalert('Success');
} else {
//	header("HTTP/1.1 500 Internal Server Error");
	rtnalert('Something terrible happened trying to talk to Instapaper :(');
	exit;
}

?>