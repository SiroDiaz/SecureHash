<?php

namespace Secure\SecureStatic;
use Exception;

class SecureHash {

	private static $algorithms = array();

	/**
	 * Returns the hash algorithms supported by PHP.
	 *
	 * @param void
	 * @return array The list of algorithms
	 */

	public static function getAlgorithms(){
		return hash_algos();
	}
  
	/**
	 * Check if the given hash algorithm is contained in PHP.
	 *
	 * @param string $algorithm The algorithm to be use
	 * @return bool Return true if the algorithm exists
	 * 			return false if it does not exists
	 */

	private static function hasAlgorithms($algorithm){

		$algorithm = strtolower($algorithm);
		self::$algorithms = self::getAlgorithms();
		$algorithmsLength = count(self::$algorithms) - 1;
		$find = false;

		while(!$find && $algorithmsLength >= 0){
			if(($algorithm == self::$algorithms[$algorithmsLength])){
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
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed $value A string, array, bool, int, float or object
	 * 				to be hashed
	 * @return string The hash value
	 */

	public static function cifrate($algorithm, $value){
		if(!self::hasAlgorithms($algorithm)){
			return false;
		}

		if(is_array($value) || is_object($value)){
			$value = serialize($value);
		}

		return hash(strtolower($algorithm), $value);
	}

	/**
	 * Returns the hashed file.
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param string $file The file (including the directory) to be hashed
	 * @return string The hash value
	 * @throws Exception message if the file requested does not exists
	 *			or if the $file variable is not a string
	 */

	public static function cifrateFile($algorithm, $file){
		if(!self::hasAlgorithms($algorithm)){
			return false;
		}

		try{
			if(!is_string($file)){
				throw new Exception("String parameters only");
			}
			if(!file_exists($file)){
				throw new Exception("The file requested does not exists");
			}else{
				$fileHandler = fopen($file, 'rb'); 
				$text = fread($fileHandler, filesize($file)); 
				fclose($fileHandler);
				unset($file);
				return hash(strtolower($algorithm), $text);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}
}

