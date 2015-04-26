<?php
require_once '../../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();
/*
 * A character information retrieval ajax script.
 * */

$response = new stdClass();
$response->server_time = time();

$characterName = htmlentities(preg_replace("/\\s+/iX", " ", $_GET['characterName']), ENT_QUOTES);
if(!empty($characterName)){
	$characterProvider = new Model_Data_CharacterProvider();
	$character = $characterProvider->getDetailsByCharacterName($characterName);
	$response->success = !empty($character);
	if($response->success){
		$response->characterInfo = $character;
		$response->type = 'registered';
		unset($response->characterInfo['player_private_notes']);
		echo json_encode($response);
		die();
	}
	// won't get here if it's a registered character.
	$guestUsersProvider = new Model_Data_GuestUsersProvider();
	$guest = $guestUsersProvider->getDetailsByCharacterName($characterName);
	if( !empty( $guest ) ){ // it's in the system.  It'll either be a registered user or a guest.
		$response->success = true;
		$response->characterInfo = $guest;
		if( $guest['username'] ){ // this is a registered user.  the userId column only exists in users_users. 
			$response->type = 'registered-guest';
		}else{ // this is a guest
			$response->type = 'guest';
		}
		echo json_encode($response);
		die();
	}
	//echo "I'm not a guest or a character<br>";
}else{
	$response->success = false;
}

echo json_encode($response);