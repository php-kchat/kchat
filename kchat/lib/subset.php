<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/


class subset{
	
	const BIN = '0';
	const OCT = '1';
	const DEC = '2';
	const HEX = '3';
	
  /**
  * @author Ganesh Kandu
  * @author Ganesh Kandu <kanduganesh@gmail.com>
  * @param void
  *	@return void
  */
	public function __construct(){
		header('Content-type: text/html; charset=utf-8');
	}

  /**
  * @author Ganesh Kandu
  * @author Ganesh Kandu <kanduganesh@gmail.com>
  * @param char $char operant to get its byte value
  * @param int $return number type of calculated value
  *	@return mix[] byte value of $char
  */	
	public function decode($char,$return = self::DEC){
		$i = 0;
		$int = null;
		while(isset($char[$i])){
			$int .= dechex(ord($char[$i]));
			$i++;
		}
		switch($return){
			case self::HEX:
				return $int;
			break;
			case self::BIN:
				return base_convert($int,16,2);
			break;
			case self::DEC:
				return base_convert($int,16,10);
			break;
			case self::OCT:
				return base_convert($int,16,8);
			break;
		}
	}

  /**
  * @author Ganesh Kandu
  * @author Ganesh Kandu <kanduganesh@gmail.com>
  * @param int mix[] operant to get its char value
  * @param int $from number type of parameter value
  *	@return char
  */	
	public function encode($int,$from = self::DEC){
		$char = null;
		switch($from){
			case self::HEX:
				$char = $int;
			break;
			case self::BIN:
				$char = base_convert($int,2,16);
			break;
			case self::DEC:
				$char = base_convert($int,10,16);
			break;
			case self::OCT:
				$char = base_convert($int,8,16);
			break;
		}
		
		$chr = array();
		$len = strlen($char);
		$i = 0;
		while($len > $i){
			$chr[] = substr($char,$i,2);
			$i = $i + 2;
		}
		
		$result = '';
		$i = 0;
		while(isset($chr[$i])){
			$result .= @chr(base_convert($chr[$i],16,10));
			$i++;
		}
		return $result;
	}
}

?>