<?php

session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}

/*$id=$_POST['id'];
var_dump($id);
$cid = $_POST['cell'];*/

include 'db_connect.php';

if(!empty($_GET)) {
	$cid = $_GET['cell'];
$stmt = $db->prepare("UPDATE cells SET blacklisted='' WHERE cell = ?");
$stmt->bind_param('s', $cid);
$stmt->execute();
$stmt->close();
$stmt1 = $db->prepare("DELETE FROM blacklist WHERE cell = ?");
$stmt1->bind_param('s', $cid);
$stmt1->execute();
$stmt1->close();
$stmt2 = $db->prepare("SELECT cell, BSC FROM cells WHERE cell = ?");
$stmt2->bind_param('s', $cid);
$stmt2->bind_result($cell, $bsc);
$stmt2->execute();
$cellbsc = [];
while($stmt2->fetch()) {
		$cellbsc[] = [
		'cell' => $cell,
		'bsc' => $bsc
		];
	file_put_contents('whitelist.txt', "@r-\n", LOCK_EX);	
	file_put_contents('whitelist.txt', "@CONNECT ". $bsc ."\n", FILE_APPEND |LOCK_EX);
	file_put_contents('whitelist.txt', "rlsvi:CELL=". $cell .",PSVTYPE=mcpaps;\n", FILE_APPEND | LOCK_EX);
}
$stmt2->close();
}

if(isset($_POST['yes'])) {
	$output = shell_exec('"C:\Program Files (x86)\murkfiol\murkfiol.exe"  /runexit C:\xampp\htdocs\mcpaps\whitelist.txt');
	echo "<pre>$output</pre>";
	echo '<div class="alert alert-success" role="alert">MCPA PS on cell '. $cell . ' is activated in live network!</div>';
	}
	else {echo '<div class="alert alert-warning" role="alert">Cell '. $cell . ' is added to whitelist and MCPA PS will be activated by script at 21:00h.</div>';}



?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MCPA Power Saving | Black list</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container">
	<?php include 'header.php'; 
		if(!isset($_POST['yes'])) { echo '<p>Do you want to activate MCPA PS on <strong> <?php echo $cell;?></strong> in live network? </p>
		<form method="POST">
			<button class="btn btn-primary" type="submit" name="yes">YES</button>
			<a href="blacklist.php" class="btn btn-primary">NO</a>
			
		</form>
		</div>';}	?>
</body>
</html>