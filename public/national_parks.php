<?php 
//variables
$heading = ['ID', 'name', 'location', 'date established', 'area in acres'];

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getOffset(){
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	return ($page - 1) * 4;
} //end of getOffset

function getParks($dbc){
	//OLD $stmt = $dbc->query('SELECT * FROM national_parks');
	$stmt = $dbc->query('SELECT * 
		FROM national_parks
		LIMIT 4 OFFSET '. getOffset() );
	$rows =  $stmt-> fetchALL(PDO::FETCH_ASSOC);
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
	<style>	
	.disabled{
		pointer-events: none;
		cursor: default;
	}
	</style>
	
</head>
<body>
	<div class="container">
		<h1>National Parks</h1>
		<table border="">
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
				<a class="disabled" href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a href="?page=<?= $nextPage; ?>" >Next &rarr;</a>		
			<? elseif($page == $numPages) : ?>
				<a href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a <a class="disabled" href="?page=<?= $nextPage; ?>" >Next &rarr;</a>		
			<? else : ?>
				<a href="?page=<?= $prevPage; ?>" > &larr; Previous</a>
				<a href="?page=<?= $nextPage; ?>" >Next &rarr;</a>	
			<? endif; ?>
		</div>	
	</div>
	
</body>
</html>