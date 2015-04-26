<?php
session_start();

// must bootstrap the ajax calls
require_once '../../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

$response = new stdClass();
$response->playerId = $userId;
$response->success = false;
$response->message = "";

if(!$userId){
	$response->message = "Error: Not logged in.";
	die(json_encode($response));	
}

$characterString = $_REQUEST['characterString'];
$message = $_REQUEST['message'];
$characterId = $_REQUEST['character_id'];
$recipientArr = explode( ',', $characterString );

$characterProvider = new Model_Data_CharacterProvider();
$characterMessageProvider = new Model_Data_CharacterMessageProvider();

foreach($recipientArr as $recipient){
	try{
		$recipientObj = $characterProvider->getOneByCharacterName($recipient);
		if( !is_object( $recipientObj ) ){ // not a valid character
			continue; // skip it
		}
		
		$characterMessage = new Model_Structure_CharacterMessage(
			array(
				'sender_user_id'=>$userId,
				'sender_character_id'=>$characterId,
				'recipient_character_id'=>$recipientObj->getCharacterId(),
				'message_title'=>'Sent From Chat',
				'message_text'=>$message,
				'date_created'=>time()
			)
		);
		$characterMessageProvider->insertOne($characterMessage, $arrErrors);
		$response->success = true;
		
		$userProvider = new Model_Data_UsersUsersProvider();
		$userData = $userProvider->getOneByPk($userId);
		
		$mailer = new SimpleMail();
		$send = $mailer->setTo( $userData->getEmail(), $userData->getLogin() )
			->setSubject( 'New Message From ' . $recipientObj->getName() )
			->setFrom( '<admin@mlpnwrp.com> NWRP Messaging System' )
			->setMessage( $message )
			->send();
	}catch(Exception $e){
		$response->message = $e->getMessage();
	}
}

echo json_encode($response);