<?php 
/*
1. Much like the address book in our example, you'll be creating an address book application 
that stores entries in a CSV file on your computer. In the same fashion as your todo.php application,
 you will want to display your entries at the top of the page, and have a form below for adding new entries.
  Each entry should take a name, address, city, state, zip, and phone.
   You can use a HTML table or definition lists for displaying the addresses.

2. Create a function to store a new entry. A new entry should have validate 5 required fields:
name, address, city, state, and zip. Display error if each is not filled out.

3. Use a CSV file to save to your list after each valid entry.

4. Open the CSV file in a spreadsheet program or text editor and verify the contents are what you expect after adding some entries.

5. Refactor your code to use functions where applicable.

Original CSV will contain
"The White House","1600 Pennsylvania Avenue NW",Washington,DC,20500,
"Marvel Comics","P.O. Box 1527","Long Island City",NY,11101,
LucasArts,"P.O. Box 29901","San Francisco",CA,94129-0901,
*/

//include classes
require_once ('classes/address_data_store.php');

//iniitailize class
$address_data_store1 = new AddressDataStore('data/ADDRESS_BOOK.CSV');//testing address_data_store lower case

//variables
$address_book = []; // holds array for addresses
$uploaded_addreses = []; //new array for uploaded files
$error_msg=''; //initailize variable to hold error messages
$heading = ['name', 'address', 'city', 'state', 'zip', 'phone', 'ACTION'];
$isValid = false; //form validation
$saved_file_items = [];//new array for uploaded address book

function storeEntry($form_data){
	$form_count = 0; //initiate variable to find out if there is form data missing
	$msg = '';
	//var_dump($form_data);
	foreach ($form_data as $data) {
		if (!empty($data)) {
			//echo 'missing data';
			$form_count++;
		} //end of if		
	} //end of foreach	
	
	if ($form_count > 4) {
		echo 'You have all your data' . PHP_EOL;		
		return true;
	} //end of if
	else {
		echo 'You are missing data' . PHP_EOL;
		return false;
	} //end of else
} //end of storeEntry

//load from CSV file
$address_book = $address_data_store1->read_csv($address_book);

//remove item from address array using GET
if (isset($_GET['remove_item']) ){
	 $removeItem = $_GET['remove_item'];	 
	 unset($address_book[$removeItem]); //remove from todo array	 
	 $address_data_store1->write_csv($address_book);
	 header('Location: /address_book.php');
	 exit(0);
} //end of remove item

//add new address from POST
if(!empty($_POST)){
	if ($isValid = storeEntry($_POST)) {
		if (empty($_POST['phone'])){
			//array_pop($_POST);
			$_POST['phone'] = '';
		} //end of no phone
		$new_address = [];    
		foreach ($_POST as $value) {
			$new_address[] = $value;
		} //end of foreach		
		$address_book[] = $new_address;
		$address_data_store1->write_csv($address_book);
		header('Location: /address_book.php');
		exit(0);	
	}  // end of valid input
} //end of if empty

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
		$uploaded_addreses = $address_data_store2->read_csv($uploaded_addreses);
		//merge uploaded and local arrays
		$address_book = array_merge($address_book, $uploaded_addreses);
		//save to file
		$address_data_store1->write_csv($address_book);	    
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
		<h4>ERROR:</h4>
		<?= $error_msg . PHP_EOL;?>
		<?= PHP_EOL;?>
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