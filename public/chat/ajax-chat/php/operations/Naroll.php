<?php
include_once 'OperationBase.php';
class Operation_Naroll extends OperationBase{
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
		$test = preg_match("/^(-?)(\d+)(S(\d+))?$/i", $this->data[0], $matches);
		$result = "";
		
		if( $test === false){
			throw new Exception("An error occurred parsing the string.");
		}elseif( $test === 0 ){
			$this->messages[] = "Usage: '/naroll [-]XSY' where X is your skill modifier and Y is your skill (Skill cannot be negative).";
		}else{
			$isNegative = $matches[1];
			$howBig = 100; 
			$modifier = intval( $isNegative . $matches[2] );
			$skill = !empty( $matches[3] ) ? intval( $matches[4] ) : 0;
			
			$tenCount = 0;
			$sum = 0;
			$silly = false;
			
			
			if($modifier > 200 || $skill > 200 ){
				$silly = true;
			}else{
				$roll = rand( 1, $howBig ); // 31
				if( $skill ){
					$test = $modifier + $skill; // 10 + 40
				}else{
					$test = $modifier;
				}
				$success = $roll < $test; // 31 < 50  success
				$difference = abs( $roll - $test ); // 19 difference
				$degrees = intval( $difference / 10 ); // every ten in the difference is a degree.  Divide and discard the remainder.
				
				$result = "{{ {$roll} < ";
				$result .= ( $skill ) ? "{$test} ( {$modifier} + {$skill} ) = ": "{$test} = ";
				$result .= ( $success ) ? "Success! " : "Failure! ";
				if($degrees == 1){
					$result .= "1 degree. ";
				}elseif( $degrees > 1){
					$result .= "$degrees degrees. ";
				}
			}
			
			if($silly)
				$result .= "Don't be silly. }} ";
			else
				$result .= "}} "; // then add the rest
		}
		
		return $result;
	}
}