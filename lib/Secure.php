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

		$this->algo  = strtolower($algo);
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
		$this->algo = strtolower($algo);
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
  
}

