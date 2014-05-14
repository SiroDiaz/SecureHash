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

		$algorithm = trim(strtolower($algorithm));
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
				throw new Exception('String parameters only');
			}
			if(!file_exists($file)){
				throw new Exception('The file requested does not exists');
			}else{
				$fileHandler = fopen($file, 'rb'); 
				$text = fread($fileHandler, filesize($file));
				fclose($fileHandler);
				unset($fileHandler);
				return hash(strtolower($algorithm), $text);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed url data downloaded.
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param string $file The file (including the directory) to be hashed
	 * @return mixed The hash value if the data has been downloaded or false if not
	 * @throws Exception message if the file requested does not exists
	 *			or if the $file variable is not a string
	 */

	public static function cifrateUrl($algorithm, $url){
		if(!self::hasAlgorithms($algorithm)){
			return false;
		}

		try{
			
			if(!is_string($url)){
				throw new Exception('String parameters only');
			}

			if(!filter_var($url, FILTER_VALIDATE_URL)){
				throw new Exception('The url requested seems to be invalid');
			}else{
				$content = file_get_contents($url);
				if(empty($content)){
					return false;
				}

				return hash(strtolower($algorithm), $content);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed values or only or a hashed value
	 * in case of the array.
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed $values An array containing diffent types of variables
	 * 				to be hashed or a string(not recommended)
	 * @return mixed $data array if there is at least more than one
	 *			array element or a string if pass a string variable
	 * @throws Exception message if pass an empty array
	 */

	public static function cifrateMultiple($algorithm, $values){
		try{
			if(is_array($values) && count($values) > 1){
				
				$data = array();
				$result = null;

				foreach ($values as $value) {
					$result = self::cifrate($algorithm, $value);
					if(is_string($result)){
						$data[] = $result;
					}else{
						return false;
					}
				}

				return $data;
			}elseif(is_array($values) && count($values) == 1){
				return self::cifrate($algorithm, $values[0]);
			}elseif(is_array($values) && empty($values)){
				throw new Exception('You must provide a non empty array');
			}else{
				return self::cifrate($algorithm, $values);
			}
		}catch(Exception $e){
			exit($e->getMessage() ."\n");
		}
	}

	/**
	 * Returns the hashed array of files.
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed  $files The files (including the directory) to be hashed
	 *					or a string if a string was passed(not recommended)
	 * @return mixed The hash value
	 * @throws Exception message if the file requested does not exists
	 *			or if the $files variable is a string
	 */

	public static function cifrateMultipleFiles($algorithm, $files){

		if(is_array($files) && count($files) > 1){

			$data = array();
			$result = null;

			foreach($files as $file){
				$result = self::cifrateFile($algorithm, $file);
				if(is_string($result)){
					$data[] = $result;
				}else{
					return false;
				}
			}

			return $data;
		}elseif(is_array($files) && count($files) == 1){
			return self::cifrateFile($algorithm, $files[0]);
		}elseif(is_array($files) && empty($files)){
			throw new Exception('You must provide a non empty array');
		}else{
			return self::cifrateFile($algorithm, $files);
		}

	}

	########################################################################

	public static function cifrateMultipleUrls($algorithm, $urls){

	}

	/**
	 * Compare two variables and check if they are equal or not
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed  $val1		The first value to compare
	 * @param mixed  $val2		The second value to compare
	 * @return bool true if the variables are equals or false if not
	 */

	public static function compare($algorithm, $val1, $val2){
		return (self::cifrate($algorithm, $val1) == self::cifrate($algorithm, $val2)) ? true : false;
	}

	/**
	 * Compare two files and check if they are equal or not
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed  $val1		The first file to compare
	 * @param mixed  $val2		The second file to compare
	 * @return bool true if the files are equals or false if not
	 */

	public static function compareFiles($algorithm, $file1, $file2){
		return (self::cifrateFile($algorithm, $file1) == self::cifrateFile($algorithm, $file2)) ? true : false;
	}

	/**
	 * Compare two Urls and check if they are equal or not
	 *
	 * @param string $algorithm The hash algorithm to be used
	 * @param mixed  $val1		The first url to compare
	 * @param mixed  $val2		The second url to compare
	 * @return bool true if the urls are equals or false if not
	 */

	public static function compareUrls($algorithm, $url1, $url2){
		return (self::cifrateUrl($algorithm, $url1) == self::cifrateUrl($algorithm, $url2)) ? true : false;
	}

}