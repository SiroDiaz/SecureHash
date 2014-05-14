<?php

namespace Secure;
use Exception;

class SecureHash {
    
	private $value;
	private $text;
	private $algo;
	private $algos;

	/**
	 * Initializes a Hash object and assigns both hash algorithm and value
	 * or file name to be hashed. $algo is modified to a lower case string prior to assignment.
	 * Throws an Exception if the first argument is not a string
	 *
	 * @param  string  $algo The algorithm to be used
	 * @param  mixed   $val  The data or file name(including the route) to be hashed
	 * @throws Exception if algorithm given is not a string
	 */

	public function __construct($algo, $val){

		if(!is_string($algo)){
			throw new Exception('The algorithm must be a string');
		}

		$this->algo  = trim(strtolower($algo));
		$this->value = $val;
		$this->algos = $this->getAlgorithms();

		return $this;
	}

	public function __toString(){
		return $this->value;
	}

	/**
	 * Returns the current value/file name to be hashed
	 *
	 * @return string The value to be hashed
	 */

	public function getValue(){
		return $this->value;
	}

	/**
	 * Returns the current algorithm
	 *
	 * @return string The hashing algorithm
	 */

	public function getAlgo(){
		return $this->algo;
	}

	/**
	 * Returns the encoding used by the Stringy object.
	 *
	 * @return array All the PHP available hash functions
	 */

	public function getAlgorithms(){
		return hash_algos();
	}

	/**
	 * Change the current value or file to other
	 * if the given value is not a string then serialize the data
	 *
	 * @param mixed $val The new value
	 * @return void
	 */

	public function setValue($val){
		$this->value = $val;
	}

	/**
	 * Change the current hash algorithm to other and set it in lowercase
	 *
	 * @param string $algo The new hash algorithm
	 * @return void
	 */

	public function setAlgo($algo){
		$this->algo = trim(strtolower($algo));
	}

	/**
	 * Returns the encoding used by the Stringy object.
	 *
	 * @return string The current value of the $encoding property
	 */

	private function hasAlgorithms(){

		$this->algo = strtolower($this->algo);
		$algorithmsLength = count($this->algos) - 1;
		$find = false;
    
		while(!$find && $algorithmsLength >= 0){
			if(($this->algo == $this->algos[$algorithmsLength])){
				$find = true;
			}else{
				$algorithmsLength--;
			}
		}
    
		return ($algorithmsLength >= 0) ? true : false;
	}

	/**
	 * Returns the hashed value.
	 *
	 * @param void
	 * @return string The hash value
	 */

	public function cifrate(){
		if(!$this->hasAlgorithms()){
			return false;
		}
	
		if(is_array($this->value) || is_object($this->value)){
			return hash($this->algo, serialize($this->value));
		}
		
		return hash($this->algo, $this->value);
	}

	/**
	 * Returns the hashed file.
	 *
	 * @param void
	 * @return string The hash value
	 * @throws Exception message if the file requested does not exists
	 */
  
	public function cifrateFile(){
		if(!$this->hasAlgorithms()){
			return false;
		}
		
		try{
			if(!file_exists($this->value)){
				throw new Exception('The file requested does not exists');
			}else{
				$fileHandler = fopen($this->value, 'rb'); 
				$this->text = fread($fileHandler, filesize($this->value));
				fclose($fileHandler);
				return hash($this->algo, $this->text);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed url data downloaded.
	 *
	 * @param void
	 * @return string The hash value
	 * @throws Exception message if the file requested does not exists
	 *			or if the $file variable is not a string
	 */

	public function cifrateUrl(){
		if(!$this->hasAlgorithms()){
			return false;
		}

		try{
			
			if(!is_string($this->value)){
				throw new Exception('String parameters only');
			}

			if(!filter_var($this->value, FILTER_VALIDATE_URL)){
				throw new Exception('The url requested seems to be invalid');
			}else{
				$this->text = file_get_contents($this->value);

				return hash($this->algo, $this->text);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed values or only or a hashed value
	 * in case of the array.
	 *
	 * @param mixed $values An array containing diffent types of variables
	 * 				to be hashed or a string(not recommended)
	 * @return mixed $data array if there is at least more than one
	 *			array element or a string if pass a string variable
	 * @throws Exception message if pass an empty array
	 */

	public static function cifrateMultiple($values){}

	/**
	 * Returns the hashed array of files.
	 *
	 * @param mixed  $files The files (including the directory) to be hashed
	 *					or a string if a string was passed(not recommended)
	 * @return mixed The hash value
	 * @throws Exception message if the file requested does not exists
	 *			or if the $files variable is a string
	 */

	public function cifrateMultipleFiles($files){}

	########################################################################

	public function cifrateMultipleUrls($urls){}

	/**
	 * Compare two variables and check if they are equal or not
	 *
	 * @param mixed  $val1		The first value to compare
	 * @param mixed  $val2		The second value to compare
	 * @return bool true if the variables are equals or false if not
	 */

	public static function compare($val1, $val2){}

	/**
	 * Compare two files and check if they are equal or not
	 *
	 * @param mixed  $val1		The first file to compare
	 * @param mixed  $val2		The second file to compare
	 * @return bool true if the files are equals or false if not
	 */

	public function compareFiles($file1, $file2){}

	/**
	 * Compare two Urls and check if they are equal or not
	 *
	 * @param mixed  $val1		The first url to compare
	 * @param mixed  $val2		The second url to compare
	 * @return bool true if the urls are equals or false if not
	 */

	public function compareUrls($url1, $url2){}
  
}

