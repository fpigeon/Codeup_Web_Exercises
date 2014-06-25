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
$heading = ['id','name', 'address', 'city', 'state', 'zip', 'phone', 'ACTION'];
$isValid = false; //form validation
$error_msg=''; //initailize variable to hold error messages

// Establish DB Connection
// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_addressBook_db', 'frank', 'password');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//echo $dbc->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

// // Create the query to create table for names
// $query = 'CREATE TABLE names (
//     id INT UNSIGNED NOT NULL AUTO_INCREMENT,
//     name VARCHAR(50) NOT NULL,    
//     PRIMARY KEY (id)
// )';
// // Run query, if there are errors they will be thrown as PDOExceptions
// $dbc->exec($query);

// // Create the query to create table for addresses
// $query = 'CREATE TABLE addresses (
//     id INT UNSIGNED NOT NULL AUTO_INCREMENT,
//     address VARCHAR(50) NOT NULL,
//     city VARCHAR(50) NOT NULL,
//     state VARCHAR(2) NOT NULL,
//     zip VARCHAR(20) NOT NULL,
//     phone VARCHAR(20),
//     PRIMARY KEY (id)
// )';
// // Run query, if there are errors they will be thrown as PDOExceptions
// $dbc->exec($query);

// $query = 'CREATE TABLE names_addresses_mapping (
//   name_id INT(10) UNSIGNED DEFAULT NULL,
//   address_id INT(10) UNSIGNED DEFAULT NULL,
//   FOREIGN KEY (name_id) REFERENCES names (id),
//   FOREIGN KEY (address_id) REFERENCES addresses (id)
// )';
// $dbc->exec($query);

function getTodos($dbc){
	$stmt = $dbc->prepare('SELECT * FROM todos LIMIT :LIMIT OFFSET :OFFSET');
	$stmt->bindValue(':LIMIT', LIMIT_VALUE, PDO::PARAM_INT);
	$offset_value = getOffset();
	$stmt->bindValue(':OFFSET', $offset_value, PDO::PARAM_INT);
	$stmt->execute();
	$rows =  $stmt->fetchALL(PDO::FETCH_ASSOC);	
	return $rows;	
} //end of getTodos

//----------------FROM OLD ADDRESS BOOK--------------------------------------------------------//
//variables
$address_book = []; // holds array for addresses
$uploaded_addreses = []; //new array for uploaded files
$error_msg=''; //initailize variable to hold error messages
$heading = ['name', 'address', 'city', 'state', 'zip', 'phone', 'ACTION'];
$saved_file_items = [];//new array for uploaded address book
$isValid = false; //form validation

//validate string to be over zero and under 125 characters
function stringCheck ($string){
	if (strlen($string) <= 1 || strlen($string) > 125) {
    			throw new InvalidInputException('$string must be over 0 or under 125 characters');
    } // end of excepmtion   
}//end of stringCheck

//load from CSV file
$address_book = $address_data_store1->read($address_book);

//remove item from address array using GET
if (isset($_GET['remove_item']) ){
	 $removeItem = $_GET['remove_item'];	 
	 unset($address_book[$removeItem]); //remove from todo array	 
	 $address_data_store1->write($address_book);
	 header('Location: /address_book.php');
	 exit(0);
} //end of remove item

//add new address from POST
if(!empty($_POST)){
	try{
		//add a phone if not entered
		if (empty($_POST['phone'])){
			//array_pop($_POST);
			$_POST['phone'] = '999-999-9999';
		} //end of no phone

		//ensure form entries are not empty
		foreach ($_POST as $key => $value) {				
			stringCheck($value);
		}  //end of foreach		
		$address_book[] = $_POST; // add to array
		$address_data_store1->write($address_book); //save file
		header('Location: /address_book.php'); // reload the page
		exit(0);
		// }
	} // end of try
	catch(InvalidInputException $e){
			$error_msg = $e->getMessage().PHP_EOL;
	} // end of catch				
}// end of if


//move uploaded files to the upload directory	
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
	if ($_FILES['file1']['type'] == 'text/csv'){
		$upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
	    // Grab the filename from the uploaded file by using basename
	    $filename = basename($_FILES['file1']['name']);
	    // Create the saved filename using the file's original name and our upload directory
	    $saved_filename = $upload_dir . $filename;
	    // Move the file from the temp location to our uploads directory
	    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

	    //create new instance of for uploaded CSV
		$address_data_store2 = new AddressDataStore($saved_filename);
		//parse uploaded CSV and assign to $uploaded_address array
		$uploaded_addreses = $address_data_store2->read($uploaded_addreses);
		//merge uploaded and local arrays
		$address_book = array_merge($address_book, $uploaded_addreses);
		//save to file
		$address_data_store1->write($address_book);	    
	} // end of if files are csv
    else{
    	$error_msg = 'Upload error: wrong file type. Must be .csv';
    }  // end of not csv type
} //end of if something was uploaded

?>
 <!doctype html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Address Book</title>
 </head>
 <body>
	<h1>Web Address Book</h1>
	<!-- display error message if exists -->
	<? if(!empty($error_msg)) : ?>
		<?= PHP_EOL . $error_msg . PHP_EOL;?>
		<script>alert('Something went wrong, try again');</script>
	<? endif; ?>	

	<!-- output addresses on screen in a table -->
	<table border="1">
		<tr>			
			<? foreach ($heading as $value) :?>
				<th><?= $value ?> </th>								
			<? endforeach;  ?>			
		</tr>			
		<? foreach ($address_book as $key => $address) :?>
			<tr>								
			<? foreach ($address as $value) :?>
				<!-- sanitize user input -->
				<? $value = htmlspecialchars(strip_tags($value)); ?>
				<td> <?= $value ?> </td>													
			<? endforeach;  ?>
			<td><?= "<a href=\"?remove_item=$key\">Remove Address</a>"; ?></td> 									
			</tr>				
		<? endforeach; ?>		
	</table>

	<h2>Input New Address</h2>
	<form method="POST" action="/address_book.php">		
        <label for="name">Name</label>
        <input id="name" name="name" type="text" placeholder="Address Name" value= "<?=(!$isValid && !empty($_POST['name']) ? $_POST['name'] : $POST['name'] = '') ?>">        
        <br>

		<label for="address">Address</label>
        <input id="address" name="address" type="text" placeholder="Street Address"  value= "<?=(!$isValid && !empty($_POST['address']) ? $_POST['address'] : $POST['address'] = '') ?>">
        <br>

        <label for="city">City</label>
        <input id="city" name="city" type="text" placeholder="City" value= "<?=(!$isValid && !empty($_POST['city']) ? $_POST['city'] : $POST['city'] = '') ?>">
        <br>

        <label for="state">State</label>
        <input id="state" name="state" type="text" placeholder="State" value= "<?=(!$isValid && !empty($_POST['state']) ? $_POST['state'] : $POST['state'] = '') ?>">
        <br>

        <label for="zip">Zip</label>
        <input id="zip" name="zip" type="number" placeholder="Zip Code" value= "<?=(!$isValid && !empty($_POST['zip']) ? $_POST['zip'] : $POST['zip'] = '') ?>">
        <br>

        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="tel" placeholder="Phone Number" value= "<?=(!$isValid && !empty($_POST['phone']) ? $_POST['phone'] : $POST['phone'] = '') ?>">
        <br>
        <button type="submit">Add Address</button>
	</form>

	<h2>Upload File</h2>
	<form method="POST" enctype="multipart/form-data">
	    <label for="file1">File to upload: </label>
	    <input type="file" id="file1" name="file1">
		<br>
	    <input type="submit" value="Upload">    
	</form>	
 </body>
 </html>