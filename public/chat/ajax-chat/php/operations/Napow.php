<?php
include_once 'OperationBase.php';
class Operation_Napow extends OperationBase{
	public $operator;
	public $data;
	public static $args = 1;
	public $messages = array();

	function __construct( $args ){
		if(self::$args != count($args)){
			return (implode(" ", $args));
		}

		$this->data = $args;
	}

	public function getValue(){
		try{
			return $this->roll();
		} catch (Exception $e){
			throw $e;
		}
	}

	private function roll(){
		$matches = array();
		$test = preg_match("/^(\d+)(([+-]){1}(\d+)(T(\d+))?)?$/i", $this->data[0], $matches);
		$result = "";
		
		if( $test === false){
			throw new Exception("An error occurred parsing the string.");
		}elseif( $test === 0 ){
			$this->messages[] = "Usage: '/naroll [-]XSY' where X is your skill modifier and Y is your skill (Skill cannot be negative).";
		}else{
			$numDice = $matches[1];
			$howBig = 10;
			$modifier = 0; 
			if( !empty( $matches[4] ) ){
				$modifier = intval( $matches[3] . $matches[4] );
			}
			$threshhold = 0;
			if( !empty( $matches[6] ) ){
				$threshhold = intval( $matches[6] );
			}
			$sum = 0;
			$silly = false;
			$nineCount = 0;
			
			$result = "{{ ";
			
			if($numDice > 50 || $modifier < -100 || $modifier > 100 || $threshhold > 100 ){
				$silly = true;
			}else{
				$rollArr = [];
				for( $i = 0; $i < $numDice; $i++ ){
					$rand = rand( 1, $howBig );
					$rollArr[] = $rand;
					$sum += $rand;
					if( $rand == 9 ) $nineCount++;
				}

				$result .= implode(" + ", $rollArr) . " => $sum ";
				$result .= ($modifier >= 0) ? "+{$modifier} " : "{$modifier} ";
				$result .= "=> " . ( $sum + $modifier ) . " ";
				
				if( $threshhold != 0 ){
					$result .= "> " . $threshhold . " = ";
					$result .= ( $sum + $modifier > $threshhold ) ? 'Success ' : 'Failure ';
				}else{ // no threshhold
					$result .= "= " . ( $sum + $modifier ) . " ";
				}
				$result .= "( $nineCount 9's ) ";
			}
			
			if($silly)
				$result .= "Don't be silly. }} ";
			else
				$result .= "}} "; // then add the rest
		}
		
		return $result;
	}
}