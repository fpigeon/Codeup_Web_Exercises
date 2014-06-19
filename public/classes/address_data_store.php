<?php 
require_once('classes/filestore.php');

class AddressDataStore extends Filestore{
	function __construct($file){
		//lowercase filename
		$file = strtolower($file);		
		parent::__construct($file);
	} // end of constructor
} //end of AddressDataStore