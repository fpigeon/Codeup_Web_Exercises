<?php
class Filestore {
    //properties
    public $filename = '';
    public $is_csv;

    public function __construct($file) {        
        //check the last 3 extension 
        $extension = substr($file, -3);
        if ($extension == 'csv'){
            $this->is_csv = TRUE;
        }
        else {
            $this->is_csv = FALSE;
        }
        // Sets $this->filename        
        $this->filename = $file;
    } // end of __construct

    public function read($array){
        if($this->is_csv){
            return $this->read_csv($array);
        } //end of if
        else{
            return $this->read_lines();   
        }//end of else        
    } // end of read

    public function write($array){
        if($this->is_csv){
            $this->write_csv($array);
        } //end of if
        else{
            $this->write_lines($array);   
        }//end of else      
    } // end of write
    
    /*
     READ Method
     Returns array of lines in $this->filename
    */
    private function read_lines(){
        if ( (is_readable($this->filename) && (filesize($this->filename) > 0))) {
            $handle = fopen($this->filename, 'r');
            $contents = trim(fread($handle, filesize($this->filename)));
            fclose($handle);
            //echo $contents;    
            $arrayed = explode(PHP_EOL, $contents);
            return $arrayed; 
        }//end of file found
        else {
            echo 'Error Reading File' . PHP_EOL;
            return FALSE;
        }//file not found
    } // end of read_lines

    /*
    WRITE METHOD
    Writes each element in $array to a new line in $this->filename
    */
    private function write_lines ($array){        
        $handle = fopen($this->filename, 'w');
        if (is_writeable($this->filename)){        
            foreach ($array as $list_item) {
                fwrite($handle, $list_item . PHP_EOL);
            }//end of foreach
            fclose($handle);
            return TRUE;
        } //end of ovewrite ok
        else {
            return FALSE;
        } // end of else
    } // end of write_;ines

    /*
    Reads contents of csv $this->filename, returns an array
    */
    private function read_csv($array) {
        $handle = fopen($this->filename, 'r');
        while (!feof($handle)){
            $row = fgetcsv($handle);
            if (is_array($row)){
                $array[] = $row;
            } // end of if
        } //while not end of file
        return $array;
    } // end of read_csv

    /*
    Writes contents of $array to csv $this->filename
    */
    private function write_csv($big_array){
        if(is_writable($this->filename)) {
            $handle = fopen($this->filename, 'w');
            foreach($big_array as $value){
                fputcsv($handle, $value);
            } // end of foreach
            fclose($handle);
        }  //end of if
    } // end of write_csv

} // end of Filestore