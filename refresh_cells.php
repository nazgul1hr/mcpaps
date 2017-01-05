<?php

$output = shell_exec('"C:\Program Files (x86)\murkfiol\murkfiol.exe"  /runexit C:\xampp\htdocs\mcpaps\skripta_MCPAPS_stanje.txt');
echo '<div class="alert alert-warning" role="alert">MCPA PS database is updating... Please wait.</div>';
echo "<pre>$output</pre>";
include 'db_connect.php';

$stmt = $db->prepare('DELETE FROM cells');
$stmt->execute();
$stmt->close();

$databasehost = "localhost"; 
$databasename = "mcpaps"; 
$databasetable = "cells"; 
$databaseusername="root"; 
$databasepassword = ""; 
$fieldseparator = ";"; 
$lineseparator = "\n";
$csvfile = "MCPAPS.csv";

if(!file_exists($csvfile)) {
    die("File not found. Make sure you specified the correct path.");
}

try {
    $pdo = new PDO("mysql:host=$databasehost;dbname=$databasename", 
        $databaseusername, $databasepassword,
        array(
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
} catch (PDOException $e) {
    die("database connection failed: ".$e->getMessage());
}

$affectedRows = $pdo->exec("
    LOAD DATA LOCAL INFILE ".$pdo->quote($csvfile)." INTO TABLE `$databasetable`
      FIELDS TERMINATED BY ".$pdo->quote($fieldseparator)."
      LINES TERMINATED BY ".$pdo->quote($lineseparator));

//echo "Loaded a total of $affectedRows records from this csv file.\n";



$stmt = $db->prepare('SELECT cell FROM blacklist');
$stmt->bind_result($cell);
$stmt->execute();
$cells = [];
while($stmt->fetch()) {
		$cells[] = ['cell' => $cell];
}
$stmt->close();
//var_dump($cells);
foreach($cells as $cell) {
	$stmt = $db->prepare("UPDATE cells SET blacklisted='YES' WHERE cell = ?");
	$stmt->bind_param('s', $cell['cell']);
	$stmt->execute();
	$stmt->close();
}

header('Location: index.php');


?>