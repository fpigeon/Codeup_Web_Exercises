<?php 
//variables
$heading = ['ID', 'name', 'location', 'date established', 'area in acres'];

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getParks($dbc){
	$stmt = $dbc->query('SELECT * FROM national_parks');
	$rows =  $stmt-> fetchALL(PDO::FETCH_ASSOC);
	return $rows;
} //end of getUsers

$parks = getParks($dbc);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>National Parks</title>
</head>
<body>
	<h1>National Parks</h1>
	<table border="1">
		<!-- heading row -->
		<tr>			
			<? foreach ($heading as $value) :?>
				<th><?= $value ?> </th>								
			<? endforeach;  ?>			
		</tr>
		<!-- data from table -->
		<? foreach ($parks as $park) :?>
		<tr>								
			<? foreach ($park as $park_value) :?>
				<!-- sanitize user input -->
				<? $park_value = htmlspecialchars(strip_tags($park_value)); ?>
				<td> <?= $park_value ?> </td>													
			<? endforeach;  ?>			
		</tr>				
		<? endforeach; ?>			
			
	</table>
</body>
</html>