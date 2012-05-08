<?php
$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

$query = $db->prepare('SELECT * FROM users WHERE `id` = ? LIMIT 1');
$query->execute(array($_SESSION['user']));
$user = $query->fetch(PDO::FETCH_ASSOC);
?><!DOCTYPE html>
<title>Instaswap &endash; Home</title>
<link rel="stylesheet" href="style.css">
<h1>Instaswap</h1>
<p>Hi there, <?= htmlspecialchars($user['name'])?>!</p>