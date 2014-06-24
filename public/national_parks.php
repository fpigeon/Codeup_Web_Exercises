<?php 
//classes
class InvalidInputException extends Exception { }
//constants
define ('LIMIT_VALUE', 4);
//variables
$heading = ['ID', 'name', 'location', 'date established', 'area in acres', 'description' ];
$isValid = false; //form validation
$error_msg=''; //initailize variable to hold error messages

// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//validate string to be over zero and under 125 characters
function stringCheck ($string){
	if (strlen($string) <= 1 || strlen($string) > 125) {
    			throw new InvalidInputException('$string must be over 0 or under 125 characters');
    } // end of excepmtion   
}//end of stringCheck

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

//add new address from POST
if(!empty($_POST)){
	try {
		//ensure form entries are not empty
		foreach ($_POST as $value) {				
			stringCheck($value);
		}  //end of foreach		

		// Get new instance of PDO object
		$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'frank', 'password');

		// Tell PDO to throw exceptions on error
		$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $dbc->prepare('INSERT INTO national_parks (name, location, date_established, area_in_acres, description)
	                       VALUES (:name, :location, :date_established, :area_in_acres, :description)');
		
	    $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
	    $stmt->bindValue(':location', $_POST['location'], PDO::PARAM_STR);
	    $stmt->bindValue(':date_established', $_POST['date_established'], PDO::PARAM_STR);
	    $stmt->bindValue(':area_in_acres', $_POST['area_in_acres'], PDO::PARAM_INT);
	    $stmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);    

	    $stmt->execute();    
	} //end of try
	catch (InvalidInputException $e) {
		$error_msg = $e->getMessage().PHP_EOL;
	} // end of catch
	
	
}// end of if
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
		<!-- display error message if exists -->
		<? if(!empty($error_msg)) : ?>
			<?= PHP_EOL . $error_msg . PHP_EOL;?>
			<script>alert('Something went wrong, try again');</script>
		<? endif; ?>	
		
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

	<div id="form area" class="container">
		<h2>Input New Park</h2>
		<form method="POST" action="/national_parks.php">		
	        <label for="name">Name</label>
	        <input id="name" name="name" type="text" placeholder="Park Name" value= "<?=(!$isValid && !empty($_POST['name']) ? $_POST['name'] : $POST['name'] = '') ?>">        
	        <br>

			<label for="address">Location</label>
	        <input id="location" name="location" type="text" placeholder="Park Location" value= "<?=(!$isValid && !empty($_POST['location']) ? $_POST['location'] : $POST['location'] = '') ?>">
        	<br>

	        <label for="date_established">Date Established</label>
	        <input id="date_established" name="date_established" type="date" placeholder="YYYY-MM-DD" value= "<?=(!$isValid && !empty($_POST['date_established']) ? $_POST['date_established'] : $POST['date_established'] = '') ?>">
	        <br>

	        <label for="area_in_acres">Area in Acres</label>
	        <input id="area_in_acres" name="area_in_acres" type="float" placeholder="area_in_acres" value= "<?=(!$isValid && !empty($_POST['area_in_acres']) ? $_POST['area_in_acres'] : $POST['area_in_acres'] = '') ?>">
	        <br>

	        <label for="description">Description</label>
	        <br>
	        <textarea name="description" id="description" cols="30" rows="10"
	        value= "<?=(!$isValid && !empty($_POST['zip']) ? $_POST['zip'] : $POST['zip'] = '') ?>">Park Description</textarea>	        
	        <br>	        
	        <button type="submit">Add Park</button>
		</form>
	</div>
	
</body>
</html>