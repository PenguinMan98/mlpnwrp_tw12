<?php
// Copyright (C) 2013 Pengy Programming. All rights reserved.

// must bootstrap the ajax calls
require_once '../../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

$response = new stdClass();

// verify login
$handle = htmlentities(preg_replace("/\\s+/iX", " ", $_GET['handle']), ENT_QUOTES);
if($userId) { // logged in + registered char
	$characterHelper = new Model_Data_CharacterProvider();
	$character = $characterHelper->getOneByCharacterName($handle);
}
if(!$userId || !is_object($character)){ // guest
	$guestUserHelper = new Model_Data_GuestUsersProvider();
	$guestUser = $guestUserHelper->getOneByPk($handle);
}
if((!is_object($character) && !is_object($guestUser)) || // no character match or
		(is_object($character) && !$character->getLoggedIn()) ){ // registered character isn't logged in
	$response->success = false;
	$response->error = "Character Not Logged In";
	echo json_encode($response);
	die();
}

$response->success = true;

include_once 'init.php'; /*Get the INIT*/
  
$roomId = intval($_GET['room']);
$currentRoom = (is_object($character)) ? $character->getChatRoomId() : $guestUser->getChatRoomId();
$ADMIN_ROOM = 27;
  
try{
	$uugProvider = new Model_Data_UsersUsergroupsProvider(); // my own class for checking tiki permissions
	if($roomId == $ADMIN_ROOM && !$uugProvider->isGuide($userId)){ // careful.
		// check to see if their room is admin room already
		if($currentRoom != $ADMIN_ROOM){ // if it's not, no permission
			throw new Exception("You don't have permission to enter this room.");
		}
		// cuz if it is 27, then they were moved there by an admin.
	}
	if($currentRoom == $ADMIN_ROOM && !$uugProvider->isGuide($userId)){
		throw new Exception("You don't have permission to leave.");
	}
	$arrErrors = array();
	if(is_object($character)){
		$character->setChatRoomId($roomId);
		$characterHelper->updateOne($character, $arrErrors);
	}else{
		$guestUser->setChatRoomId($roomId);
		$guestUserHelper->updateOne($guestUser, $arrErrors);
	}
	if(!empty($arrErrors)){
		throw new Exception("Error changing rooms");
	}
}catch(Exception $e){
  	$response->success = false;
  	$response->error = $e->getMessage();
}

$encodedResponse = json_encode($response);
echo $encodedResponse;

?>