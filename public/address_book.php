<?php 
/*
Original CSV will contain
"The White House","1600 Pennsylvania Avenue NW",Washington,DC,20500,
"Marvel Comics","P.O. Box 1527","Long Island City",NY,11101,
LucasArts,"P.O. Box 29901","San Francisco",CA,94129-0901,
*/

//include classes
require_once ('classes/address_data_store.php');

//classes
class InvalidInputException extends Exception { }

//initialize class
$address_data_store1 = new AddressDataStore('data/ADDRESS_BOOK.CSV');//testing address_data_store lower case

//variables
$address_book = []; // holds array for addresses
$uploaded_addreses = []; //new array for uploaded files
$error_msg=''; //initailize variable to hold error messages
$heading = ['name', 'address', 'city', 'state', 'zip', 'phone', 'ACTION'];
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
			<? foreach ($address as $address_data) :?>
				<!-- sanitize user input -->
				<? $address_data = htmlspecialchars(strip_tags($address_data)); ?>
				<td> <?= $address_data ?> </td>													
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