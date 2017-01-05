<?php

session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}
if(!empty($_GET)) {
$id=$_GET['cell'];
}
//var_dump($id);


include 'db_connect.php';

if(isset($_POST['cid'])) {
	$query = $db->prepare('SELECT cell FROM cells WHERE cell = ?');
	$query->bind_result($cellid);
	$query->bind_param('s', $_POST['cid']);
	$query->execute();
	$query->store_result();
	if($query->num_rows == 1) {
		$cid = $_POST['cid'];
		$stmt = $db->prepare("UPDATE cells SET blacklisted='YES' WHERE cell = ?");
		$stmt->bind_param('s', $cid);
		$stmt->execute();
		$stmt = $db->prepare("INSERT INTO blacklist (cell) VALUES (?)");
		$stmt->bind_param('s', $cid);
		$stmt->execute();
		$stmt->close();
		} else {
			$error = ' does not exist or cell id is incorrect!';
		}
	$query->close();
	}
	
$stmt = $db->prepare("SELECT cell, cells.BSC, MCPA_active, MCPAPSHYST, MINREQTCH, blacklisted FROM cells LEFT JOIN bscs ON cells.bsc = bscs.bsc WHERE blacklisted='YES' ORDER BY cell");
//$stmt->bind_param('i', $id);
$stmt->bind_result($cell, $bsc, $MCPA_active, $mcpapshyst, $minreqtch, $blacklisted);
$stmt->execute();
$cellbsc = [];
file_put_contents('blacklist.txt', "@r-\n");
while($stmt->fetch()) {
		$cellbsc[] = [
		'cell' => $cell,
		'bsc' => $bsc,
		'MCPA_active' => $MCPA_active,
		'MCPAPSHYST' => $mcpapshyst,
		'MINREQTCH' => $minreqtch,
		'blacklisted' => $blacklisted,
	];
	file_put_contents('blacklist.txt', "@CONNECT ". $bsc ."\n", FILE_APPEND | LOCK_EX);
	file_put_contents('blacklist.txt', "rlsve:CELL=". $cell .",PSVTYPE=mcpaps;\n", FILE_APPEND | LOCK_EX);
}
$stmt->close();

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
		<?php include 'header.php'; ?>
		<?php if(isset($error)) { ?>
		<div class="alert alert-danger">Cell <strong><?php echo $_POST['cid'] . ' '?></strong><?php echo $error; ?></div>
		<?php } ?>
		<form method="POST">
			<div class="form-group">
				<label for="cid">Cell</label>
				<?php if (isset ($id)) { ?><input type="text" class="form-control" name="cid" id="cid" required  style="width: 20%" value="<?php echo $id; ?>">
				<?php } else {?> <input type="text" class="form-control" name="cid" id="cid" required  style="width: 20%">
				<?php } ?>
			</div>		

			<button class="btn btn-primary" type="submit">Add to black list</button>
		</form>
		<br><br>
		<h3>Blacklisted cells:</h3><br>
		<table class="table">
			<thead>
				<tr>
					<th class="col-md-2">Cell</th>
					<th class="col-md-2">BSC</th>
					<th class="col-md-2">MCPA PS</th>
					<th class="col-md-2">MCPAPSHYS</th>
					<th class="col-md-2">MINREQTCH</th>
					<th class="col-md-2">MCPA PS blacklisted</th>
					<th class="col-md-2"></th>
				</tr>
			</thead>

			<tbody>
				<?php foreach($cellbsc as $cell) { ?>
				<tr>
					<td><?php echo $cell['cell']; ?></td>
					<td><?php echo $cell['bsc']; ?></td>
					<td><?php echo $cell['MCPA_active']; ?></td>
					<td><?php echo $cell['MCPAPSHYST']; ?></td>
					<td><?php echo $cell['MINREQTCH']; ?></td>
					<td><?php echo $cell['blacklisted']; ?></td>
					<td><a href="remove.php?cell=<?php echo $cell['cell']; ?>" class="btn btn-warning">Remove from black list</a></td>
				</tr>
				
				<?php } ?>
			</tbody>
		</table>
		
		<br><br><br>
		<a href="transmit.php" class="btn btn-primary">Deactivate MCPA PS on blacklisted cells in live network</a>
	</div>	
</body>
</html>