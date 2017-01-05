<?php

session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}

$output = shell_exec('"C:\Program Files (x86)\murkfiol\murkfiol.exe"  /runexit C:\xampp\htdocs\mcpaps\blacklist.txt');
echo "<pre>$output</pre>";

header('Location: blacklist.php');

?>