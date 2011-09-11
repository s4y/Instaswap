<!DOCTYPE html>

<html>
<head>
	<title>Instaswap</title>
</head>
<body>
<? if ($_GET['key']): ?>
<p>Here be your bookmarklet: <a href="javascript:(function()%7Bvar%20d%3Ddocument%2Cb%3Dd.body%2Cl%2Ce%3DencodeURIComponent%2Cce%3D'createElement'%2Cac%3D'appendChild'%2Cde%3D'documentElement'%2Cq%3Dprompt(%22Instaswap%5Cn%5CnSend%20this%20page%20to%E2%80%A6%22)%3Bif(q)%7Bif(!b)%7Bb%3Dd%5Bce%5D('body')%3Bif(!d%5Bde%5D)%7Breturn%3B%7Dd%5Bde%5D%5Bac%5D(b)%3B%7Dl%3Dd%5Bce%5D('scr'%2B'ipt')%3Bl.setAttribute('src'%2C'http%3A%2F%2Finstaswap.sidnicio.us%2Fapi%3Fkey%3D<?=urlencode($_GET['key'])?>%26target%3D'%2Be(q)%2B'%26url%3D'%2Be(d.location.href))%3Bb%5Bac%5D(l)%3B%7D%7D)()%3Bvoid(0)%3B">Instaswap</a>.</p>
<? else: ?>
<h1>Oh. Hi, there!</h1>
<? endif; ?>