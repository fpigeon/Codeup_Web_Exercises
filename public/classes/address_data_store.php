<?php
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

    // function __destruct() {
    //     echo 'Class dismissed'. PHP_EOL;
    // }

} //end of AddressDataStore