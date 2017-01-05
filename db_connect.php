<?php

$db = @new mysqli('localhost', 'root', '', 'mcpaps');

if($db->connect_error) {
	echo 'Došlo je do greške prilikom spajanja na bazu podataka: ' . $db->connect_error;
	die(); 
}

if(!$db->set_charset('utf8')) { 
	echo 'Došlo je do greške kod postavljanja charseta.';
	die();
}