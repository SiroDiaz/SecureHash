<?php

namespace Secure\SecureStatic;
use Exception;

class Secure {

	private static $algorithms = array();

  	############################################################
  
	public static function getAlgorithms(){
		return hash_algos();
	}
  
  	############################################################

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

  	############################################################

	public static function cifrate($algorithm, $value){
		if(!self::hasAlgorithms($algorithm)){
			return false;
    	}
    
    	return hash(strtolower($algorithm), $value);
	}
  
  	public static function cifrateFile($algorithm, $file){
    	if(!self::hasAlgorithms($algorithm)){
  			return false;
    	}

    	try{
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

