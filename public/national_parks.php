<?php 
//constants
define ('LIMIT_VALUE', 4);
//variables
$heading = ['ID', 'name', 'location', 'date established', 'area in acres', 'description' ];

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getOffset(){
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	return ($page - 1) * 4;
} //end of getOffset

function getParks($dbc){
	$stmt = $dbc->prepare('SELECT * FROM national_parks LIMIT :LIMIT OFFSET :OFFSET');
	$stmt->bindValue(':LIMIT', LIMIT_VALUE, PDO::PARAM_INT);
	$offset_value = getOffset();
	$stmt->bindValue(':OFFSET', $offset_value, PDO::PARAM_INT);
	$stmt->execute();
	$rows =  $stmt->fetchALL(PDO::FETCH_ASSOC);	
	return $rows;	
} //end of getUsers

//get all the national park table data into $parks array
$parks = getParks($dbc);
$count = $dbc->query('SELECT count(*) FROM national_parks')->fetchColumn();
$numPages = ceil($count / 4);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$nextPage = $page + 1;
$prevPage = $page - 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>National Parks</title>
	<!-- JQuery -->	
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	
</head>
<body>
	<div class="container">
		<h1>National Parks</h1>
		<table class="table table-striped table-hover">
			<!-- heading row -->
			<tr>			
				<? foreach ($heading as $value) :?>
					<th><?= $value ?> </th>								
				<? endforeach;  ?>			
			</tr>
			<!-- data from table -->
			<? foreach ($parks as $park) :?>
			<tr>								
				<? foreach ($park as $park_value): ?>				
					<td> <?= $park_value ?> </td>													
				<? endforeach;  ?>			
			</tr>				
			<? endforeach; ?>				
		</table>
		<div id="pagination">
			<? if ($page == 1) : ?>
				<a class="btn-primary btn btn-lg" disabled="disabled" href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a class="btn btn-primary btn-lg active" href="?page=<?= $nextPage; ?>" >Next &rarr;</a>		
			<? elseif ($page == $numPages) : ?>
				<a class="btn btn-primary btn-lg active" href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a class="btn-primary btn btn-lg" disabled="disabled" href="?page=<?= $nextPage; ?>" >Next &rarr;</a>		
			<? else: ?>
				<a class="btn btn-primary btn-lg active" href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a class="btn btn-primary btn-lg active" href="?page=<?= $nextPage; ?>" >Next &rarr;</a>
			<? endif; ?>
		</div>	
	</div>
	
</body>
</html>