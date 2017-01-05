<?php
/*session_start();
if(!isset($_SESSION['username'])) {
	header('Location: login.php');
}*/
$id=$_GET['id'];
include 'db_connect.php';

$stmt = $db->prepare("SELECT cell, cells.BSC, MCPA_active, MCPAPSHYST, MINREQTCH, blacklisted FROM cells LEFT JOIN bscs ON cells.bsc = bscs.bsc WHERE id= ?");
$stmt->bind_param('i', $id);
$stmt->bind_result($cell, $bsc, $MCPA_active, $mcpapshyst, $minreqtch, $blacklisted);
$stmt->execute();
$cellbsc = [];
while($stmt->fetch()) {
		$cellbsc[] = [
		'cell' => $cell,
		'bsc' => $bsc,
		'MCPA_active' => $MCPA_active,
		'MCPAPSHYST' => $mcpapshyst,
		'MINREQTCH' => $minreqtch,
		'blacklisted' => $blacklisted,
	];
}
$stmt->close();
//var_dump($cellbsc);
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MCPA Power Saving </title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container">
		<?php include 'network.php'; ?>

		

		<table class="table">
			<thead>
				<tr>
					<th class="col-md-2">Cell</th>
					<th class="col-md-2">BSC</th>
					<th class="col-md-2">MCPA PS</th>
					<th class="col-md-2">MCPAPSHYS</th>
					<th class="col-md-2">MINREQTCH</th>
					<th class="col-md-2">MCPA PS blacklisted</th>
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
					<?php if($cell['blacklisted'] == 'YES') { ?>
					<td><?php echo $cell['blacklisted']; ?></td>
					<?php } else { ?>
					<td><a href="blacklist.php?cell=<?php echo $cell['cell']; ?>" class="btn btn-primary">Add to blacklist</a></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>