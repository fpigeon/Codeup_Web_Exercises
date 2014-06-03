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
*/
//variables
$address_book = []; // holds array for addresses
$file_path='data/address_book.csv'; //local csv file
$error_msg=''; //initailize variable to hold error messages
$heading = ['name', 'address', 'city', 'state', 'zip', 'phone'];
$isValid = true; //form validation

//Function reads a CSV file and adds it to the incoming array
function readCSV($filename, $array){
	$handle = fopen($filename, 'r');
	while (!feof($handle)){
    	$row = fgetcsv($handle);
    	if (is_array($row)){
        	$array[] = $row;
    	} // end of if
	} //while not end of file
	return $array;
} // end of readCSV

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
	//$msg = ($form_count > 4) ? 'You have all your data'  : 'You are missing data' ;
	//echo $msg;
	
	if ($form_count > 4) {
		echo 'You have all your data' . PHP_EOL;		
		return true;
	} //end of if
	else {
		echo 'You are missing data' . PHP_EOL;
		return false;
	} //end of else
} //end of storeEntry

function write_csv($big_array, $filename){
    if(is_writable($filename)) {
     	$handle = fopen($filename, 'w');
        foreach($big_array as $value){
        	fputcsv($handle, $value);
        } // end of foreach
    fclose($handle);
    }  //end of if
} // end of write_csv

//load from CSV file
$address_book = readCSV($file_path, $address_book);

//add new address from POST
if(!empty($_POST)){
	if ($isValid = storeEntry($_POST)) {
		if (empty($_POST['phone'])){
			array_pop($_POST);
		} //end of no phone
		$new_address = [];    
		foreach ($_POST as $value) {
			$new_address[] = $value;
		} //end of foreach
		//array_push($address_book, $new_address);

		$address_book[] = $new_address;
		write_csv($address_book, $file_path);
		//header('Location: /address_book.php');
		//exit(0);	
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
			
			<? foreach ($address_book as $address) :?>
				<tr>
				<!-- sanitize user input -->
				<?// $address = htmlspecialchars(strip_tags($address)); ?>
				
				<? foreach ($address as $value) :?>
						<td> <?= $value ?> </td>							
					<? endforeach;  ?>				
				<?// = "<tr>$address <a href=\"http://codeup.dev/address_book.php?remove_item=$key\">Remove Item</a></tr>\n"; ?>
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