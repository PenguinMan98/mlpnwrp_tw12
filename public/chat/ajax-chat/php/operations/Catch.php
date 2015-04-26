<?php
include_once('OperationBase.php');

/*
 * Grant
 * Gives access to a character to a player.  
 * This access does not remove or invalidate any other players access to the character.
 * /grant <player_name> <character_name>
 * */
class Operation_Catch extends OperationBase{
	public $operator;
	public $data;
	public static $args = 1; // character
	public $messages = array();

	function __construct( $args ){
		if(self::$args != count($args)){
			return (implode(" ", $args));
		}
		$this->data = $args;
	}

	public function getValue(){
		try{
			return $this->execute();
		} catch (Exception $e){
			throw $e;
		}
	}

	private function execute(){
		GLOBAL $userId;
		$uugProvider = new Model_Data_UsersUsergroupsProvider(); // my own class for checking tiki permissions
		if(!$uugProvider->isGuide($userId)){
			$this->messages[] = "You don't have permission to do that.";
			return "{{ Error 1 }}";
		}
		// ok. we have permission to proceed.
		$characterName = str_replace('"', '', $this->data[0]);
		
		// pull the character
		$cProvider = new Model_Data_CharacterProvider();
		$character = $cProvider->getOneByCharacterName($characterName);
		
		if(!is_object($character)){
			$this->messages[] = "I couldn't find character $characterName.";
			return "{{ Error 2 }}";
		}
		
		$arrErrors = array();
		
		try{
			$character->setChatRoomId( 27 );
			$cProvider->updateOne( $character, $arrErrors );
			
			if(!empty($arrErrors)){
				$this->messages[] = "Something went wrong trying to catch $characterName.";
				return "{{ Error 3: ".implode('|',$arrErrors)." }}";
			}
		}catch(Exception $e){
			$this->messages[] = $e->getMessage();
			return "{{ Error 4 }}";
		}
		return "{{ $characterName has been removed to the Administrative Room }}";
	}
}