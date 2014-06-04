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

//classes
class AddressDataStore {

    public $filename = '';

    public function __construct($file = 'data/address_book.csv') {
    	$this->filename = $file;
    }


    public function read_address_book($array) {
	    $handle = fopen($this->filename, 'r');
		while (!feof($handle)){
	    	$row = fgetcsv($handle);
	    	if (is_array($row)){
	        	$array[] = $row;
	    	} // end of if
		} //while not end of file
		return $array;
    } // end of read_address_book

    public function write_address_book($big_array) 
    {
        if(is_writable($this->filename)) {
	     	$handle = fopen($this->filename, 'w');
	        foreach($big_array as $value){
	        	fputcsv($handle, $value);
	        } // end of foreach
	    	fclose($handle);
    	}  //end of if
    } //end of write_address_book

} //end of AddressDataStore

//iniitailize class
$address_data_store1 = new AddressDataStore();

//variables
$address_book = []; // holds array for addresses
$error_msg=''; //initailize variable to hold error messages
$heading = ['name', 'address', 'city', 'state', 'zip', 'phone', 'ACTION'];
$isValid = false; //form validation

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
$address_book = $address_data_store1->read_address_book($address_book);

//remove item from address array using GET
if (isset($_GET['remove_item']) ){
	 $removeItem = $_GET['remove_item'];	 
	 unset($address_book[$removeItem]); //remove from todo array	 
	 $address_data_store1->write_address_book($address_book);
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
		$address_data_store1->write_address_book($address_book);
		header('Location: /address_book.php');
		exit(0);	
	}  // end of valid input	
} //end of if something was POSTED

?>
 <!doctype html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Address Book</title>
 </head>
 <body>
	<h1>Web Address Book</h1>
	<!-- output array on screen -->
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
 </body>
 </html>