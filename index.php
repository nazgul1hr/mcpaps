<?php
session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}

include 'db_connect.php';
$stmt = $db->prepare("SELECT updated FROM cells WHERE cell='C0001A'");
$stmt->bind_result($updated);
$stmt->execute();
$datetime = [];
while($stmt->fetch()) {
		$datetime[] = ['updated' => $updated];
}
$stmt->close();

$dd = substr($updated, 4, 2);
$mm = substr($updated, 2, 2);
$yy = substr($updated, 0, 2);
$hh = substr($updated, 7, 2);
$min = substr($updated, 9, 2);

?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MCPA Power Saving</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container">
		<?php include 'header.php'; ?>
		
		<div class="alert alert-info" role="alert">MCPA PS database last updated on <strong><?php echo $dd . "." . $mm . ".20" . $yy . ". " . $hh . ":" . $min; ?></strong></div>
		<form method="POST">
			<a href="refresh_cells.php" class="btn btn-primary" >UPDATE</a>		
        	
		</form>
	</div>
</body>
</html>


