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
	 * @param  string  $algo The algorithm to be used (default md5)
	 * @param  mixed   $val  The data or file name(including the route) to be hashed (default '')
	 * @throws Exception if algorithm given is not a string
	 */

	public function __construct($algo = 'md5', $val = ''){

		if(!is_string($algo)){
			throw new Exception('The algorithm must be a string');
		}

		$this->algo  = trim(strtolower($algo));
		$this->value = $val;
		$this->algos = $this->getAlgorithms();
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

	public function cifrateMultiple($values){
		try{
			if(is_array($values) && count($values) > 1){
				
				$data = array();
				$result = null;

				foreach ($values as $value) {
					$this->setValue($value);
					$result = $this->cifrate();
					if(is_string($result)){
						$data[] = $result;
					}else{
						return false;
					}
				}

				return $data;
			}elseif(is_array($values) && count($values) == 1){
				$this->setValue($values[0]);
				return $this->cifrate();
			}elseif(is_array($values) && empty($values)){
				throw new Exception('You must provide a non empty array');
			}else{
				$this->setValue($values);
				return $this->cifrate();
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed array of files.
	 *
	 * @param mixed  $files The files (including the directory) to be hashed
	 *					or a string if a string was passed(not recommended)
	 * @return mixed The hash value
	 * @throws Exception message if the file requested does not exists
	 *			or if the $files variable is a string
	 */

	public function cifrateMultipleFiles($files){
		try{
			if(is_array($files) && count($files) > 1){

				$data = array();
				$result = null;

				foreach($files as $file){
					if(!is_file($file)){
						return false;
					}
					$this->setValue($file);
					$result = $this->cifrateFile();
					if(is_string($result) && !empty($result)){
						$data[] = $result;
					}else{
						return false;
					}
				}

				return $data;
			}elseif(is_array($files) && count($files) == 1){
				$this->setValue($files[0]);
				return $this->cifrateFile();
			}elseif(is_array($files) && empty($files)){
				throw new Exception('You must provide a non empty array');
			}else{
				$this->setValue($files);
				return $this->cifrateFile($files);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}

	}

	/**
	 * Returns the hashed array of urls.
	 *
	 * @param mixed  $urls The urls to be hashed
	 *					or a string if a string was passed(not recommended)
	 * @return mixed The hash value
	 * @throws Exception message if the url requested seems to be invalid
	 */

	public function cifrateMultipleUrls($urls){
		try{
			if(is_array($urls) && count($urls) > 1){

				$data = array();
				$result = null;

				foreach($urls as $url){
					$this->setValue($url);
					$result = $this->cifrateUrl();
					if(is_string($result) && !empty($result)){
						$data[] = $result;
					}else{
						return false;
					}
				}

				return $data;
			}elseif(is_array($urls) && count($urls) == 1){
				$this->setValue($urls[0]);
				return $this->cifrateUrl();
			}elseif(is_array($urls) && empty($urls)){
				throw new Exception('You must provide a non empty array');
			}else{
				$this->setValue($urls);
				return $this->cifrateUrl($urls);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Compare two variables and check if they are equal or not
	 *
	 * @param mixed  $val1		The first value to compare
	 * @param mixed  $val2		The second value to compare
	 * @param string $algorithm The algorithm to use
	 * @return bool true if the variables are equals or false if not
	 */

	public function compare($val1, $val2, $algorithm = ''){
		if(!empty($algorithm)){
			$this->setAlgo($algorithm);
		}

		$this->setValue($val1);
		$val1 = $this->cifrate();
		$this->setValue($val2);
		$val2 = $this->cifrate();
		
		return ($val1 == $val2) ? true : false;
	}

	/**
	 * Compare two files and check if they are equal or not
	 *
	 * @param string  $file1		The first file to compare
	 * @param string  $file2		The second file to compare
	 * @param string  $algorithm 	The algorithm to use
	 * @return bool true if the files are equals or false if not
	 */

	public function compareFiles($file1, $file2, $algorithm = ''){
		if(!empty($algorithm)){
			$this->setAlgo($algorithm);
		}

		$this->setValue($file1);
		$file1 = $this->cifrateFile();
		$this->setValue($file2);
		$file2 = $this->cifrateFile();
		
		return ($file1 == $file2) ? true : false;
	}

	/**
	 * Compare two Urls and check if they are equal or not
	 *
	 * @param string  $val1		 The first url to compare
	 * @param string  $val2		 The second url to compare
	 * @param string  $algorithm The algorithm to use
	 * @return bool true if the urls are equals or false if not
	 */

	public function compareUrls($url1, $url2, $algorithm = ''){
		if(!empty($algorithm)){
			$this->setAlgo($algorithm);
		}

		$this->setValue($url1);
		$url1 = $this->cifrateUrl();
		$this->setValue($url2);
		$url2 = $this->cifrateUrl();

		return ($url1 == $url2) ? true : false;
	}

}