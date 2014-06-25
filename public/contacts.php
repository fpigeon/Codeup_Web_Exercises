<?php 
/*
1. Create a database, and three tables: one to hold names, one to hold addresses, and one to associate names with addresses. Addresses should be allowed to have many names, and names should be allowed to have many addresses.
2. Update the address book application to use MySQL instead of CSV files. Allow user to input new name, and either select from address list (in select dropdown) or add a new address.
3. If a new address is added, first check to see that the address, city, state, and zip combination are not already in the database. If the address already there, then use it instead of creating a new one.
4. Have all displayed names link to a page that shows the name, and all addresses associated with that person.
5. Have all displayed addresses link to a page that shows the address, and all names associated with it.
6. Be sure to use prepared statements for all queries that could contain user input.
7. Add pagination. This should display 10 addresses per page on the listing page. When your list has over 10 records, there should be buttons to allow you to navigate forward and backwards through the "pages" of todos.
8. BONUS: Add a unique constraint on the combined columns for address, city, state, and zip.
9. BONUS: Add a select input that allows the user to pick 10, 25, 50, or 100 results per page. Have the pagination respect the page size on all pages.

REFERENCE:
http://app.codeup.com/students/units/72/sub_units/261
*/
class InvalidInputException extends Exception { }
//constants
define ('LIMIT_VALUE', 10);
//variables 
$heading = ['Id','Name', 'Phone Number', '# of Addresses', 'Actions'];
$isValid = false; //form validation
$error_msg=''; //initailize variable to hold error messages

// Establish DB Connection
// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_addressBook_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//echo $dbc->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

// // Create the query to create table for names
// $query = 'CREATE TABLE contact (
//     id INT UNSIGNED NOT NULL AUTO_INCREMENT,
//     name VARCHAR(50) NOT NULL,    
//     phone CHAR(12),
//     PRIMARY KEY (id)
// )';
// // Run query, if there are errors they will be thrown as PDOExceptions
// $dbc->exec($query);

// // Create the query to create table for addresses
// $query = 'CREATE TABLE addresses (
//     id INT UNSIGNED NOT NULL AUTO_INCREMENT,
//     address VARCHAR(50) NOT NULL,
//     city VARCHAR(50) NOT NULL,
//     state CHAR(2) NOT NULL,
//     zip CHAR(5) NOT NULL,    
//     PRIMARY KEY (id)
// )';
// // Run query, if there are errors they will be thrown as PDOExceptions
// $dbc->exec($query);

// $query = 'CREATE TABLE names_addresses_mapping (
//   contact_id INT(10) UNSIGNED DEFAULT NULL,
//   address_id INT(10) UNSIGNED DEFAULT NULL,  
//   PRIMARY KEY (contact_id, address_id),
//   FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE,  
//   FOREIGN KEY (address_id) REFERENCES addresses (id) ON DELETE CASCADE
// )';
// $dbc->exec($query);

//validate string to be over zero and under 125 characters
function stringLengthCheck($string, $min=1, $max=125){
	if (strlen($string) <= $min || strlen($string) > $max) {
    			throw new InvalidInputException('$string must be over '.$min.' or under '.$max.' characters');
    } // end of excepmtion   
}//end of stringCheck

function getOffset(){
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	return ($page - 1) * LIMIT_VALUE;
} //end of getOffset

function getContact($dbc){
	$stmt = $dbc->prepare('SELECT * FROM contact LIMIT :LIMIT OFFSET :OFFSET');
	$stmt->bindValue(':LIMIT', LIMIT_VALUE, PDO::PARAM_INT);
	$offset_value = getOffset();
	$stmt->bindValue(':OFFSET', $offset_value, PDO::PARAM_INT);
	$stmt->execute();
	$rows =  $stmt->fetchALL(PDO::FETCH_ASSOC);	
	return $rows;	
} //end of getNames

//Check if something Posted
if(!empty($_POST)){		
	try {
		// Get new instance of PDO object
		$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_addressBook_db', 'frank', 'password');

		// Tell PDO to throw exceptions on error
		$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//a. is item being added => add todo!
		if (isset($_POST['name'])){
			//ensure form entries are not empty or over 125 chars			
			stringLengthCheck($_POST['name']);
			stringLengthCheck($_POST['phone_number']);
			$stmt = $dbc->prepare('INSERT INTO contact (name, phone)
	                       VALUES (:name, :phone)');		
		    $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
		    $stmt->bindValue(':phone', $_POST['phone_number'], PDO::PARAM_STR);
		    $stmt->execute();
		    //header('Location: /todo_list_db.php');
			//exit(0);	
		} //end if POST addForm

		//b. is item being removed => remove todo
		if (isset($_POST['remove'])){
			$stmt = $dbc->prepare('DELETE FROM contact WHERE id = :ID');		
		    $stmt->bindValue(':ID', $_POST['remove'], PDO::PARAM_INT);
		    $stmt->execute();
		 	//header('Location: /todo_list_db.php');
			//exit(0);
		} // end of if POST remove
	} //end of try
	catch (InvalidInputException $e) {
		$error_msg = $e->getMessage().PHP_EOL;
	} // end of catch
}// end of if

//Query db for total contact count
$count = $dbc->query('SELECT count(*) FROM contact')->fetchColumn();

//Determine pagination values
$numPages = ceil($count / LIMIT_VALUE);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$nextPage = $page + 1;
$prevPage = $page - 1;

//Query for todos on current page
$contacts = getContact($dbc);
//----------------FROM CHRIS'S SNIPPLET--------------------------------------------------------//
?>

<html>
<head>
	<title>Address Book: Contacts</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
	<h1>Address Book: Contacts</h1>
	<!-- error message if present --> 
		<? if(!empty($error_msg)) : ?>
			<?= PHP_EOL . $error_msg . PHP_EOL;?>
			<script>alert('Something went wrong, try again');</script>
		<? endif; ?>	

	<table class="table table-striped">		
		<!-- heading for table -->
		<tr>
			<? foreach ($heading as $value) :?>
				<th><?= $value ?> </th>								
			<? endforeach;  ?>			
		</tr>
		
		<!-- data from table -->
		<? foreach ($contacts as $contact) :?>
			<tr>				
				<? foreach ($contact as $key => $contact_value): ?>
					<?= "<td>$contact_value</td>"; ?>					
				<? endforeach; ?>
				<!-- number of addresses -->
				<td>
					1
				</td>
				<!-- action buttons -->
				<td>	
					<a class="btn btn-small btn-default" href="contact_addresses.php?contact_id=1">View</a>
					<button class="btn btn-small btn-danger btn-remove" 
						data-contactid="<?= $contact['id']; ?>"
						data-contactname="<?= $contact['name']; ?>">Remove</button>

				</td>
			</tr>
		<? endforeach; ?>	
	</table>

	<div class="clearfix"></div>

	<h2>Add New Contact</h2>
	<form class="form-inline" role="form" action="contacts.php" method="POST">
		<div class="form-group">
			<label class="sr-only" for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" placeholder="Name">
		</div>
		<div class="form-group">
			<label class="sr-only" for="phone_number">Phone #</label>
			<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Phone #">
		</div>
		<button type="submit" class="btn btn-default btn-success">Add Contact</button>
	</form>

</div>

<form id="remove-form" action="contacts.php" method="post">
	<input id="remove-id" type="hidden" name="remove" value="">
</form>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
 <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script>

$('.btn-remove').click(function () {
	var contactName = $(this).data('contactname');
	var contactId = $(this).data('contactid');
	if (confirm('Are you sure you want to remove contact ' + contactName + '?')) {
		$('#remove-id').val(contactId);
		$('#remove-form').submit();
	}
});

</script>

</body>
</html>