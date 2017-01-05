<?php
session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}
include 'db_connect.php';

$stmt = $db->prepare('SELECT id, BSC FROM BSCs ORDER BY BSC');
$stmt->bind_result($id, $BSC);
$stmt->execute();
$BSCs = [];
while($stmt->fetch()) {
		$BSCs[] = ['id' => $id,
		           'BSC' => $BSC];
}
$stmt->close();
//var_dump($BSCs);
//var_dump($BSC);
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MCPA Power Saving | Network</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
	<form method="POST">
		<div class="container">
			<?php include 'header.php'; ?>
		
			<div class="form-group" style="width: 20%">
				<label for="BSCs">BSC</label>
				<select name="BSCs[]" id="BSCs" class="form-control" onchange="location = this.value;">
					<option value="network.php" selected>---</option>
					<?php foreach($BSCs as $BSC) { ?>
					<option value="bsc.php?id=<?php echo $BSC['id']; ?>""><?php echo $BSC['BSC']; ?></option>
					<?php } ?>
				</select>
			</div>
			<?php //include "bsc.php?id=$id"; ?>
		</div>
	</form>