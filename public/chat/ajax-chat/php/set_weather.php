<?php
session_start();

// must bootstrap the ajax calls
require_once '../../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

include_once 'init.php'; /*Get the INIT*/

$response = new stdClass();
$response->playerId = $userId;
$response->success = false;
$response->message = "";

$authorizedWeatherPonies = array(
	'1',
	'2',
	'7', // Variety
	'25', // Ayame
	'15', // Regulus
	'11' // Smiles
);

if( array_search( $userId, $authorizedWeatherPonies ) === false ){
	$response->message = "User not authorized.";
	exit( json_encode( $response ) );
}
// ok. we have permission to proceed.

$method = strip_tags( $_POST['method'] );
$state = strip_tags( $_POST['state'] );
$duration = intval( $_POST['duration'] );
$chatRoomId = intval( $_POST['chat_room_id'] );

if( $duration < 0 || $duration > 20160 ){ // two weeks
	$response->message = "Invalid Duration";
	exit( json_encode( $response ) );
}

$weatherStateHelper = new Model_Data_WeatherStateProvider();
$weatherState = $weatherStateHelper->getOneByName( $state );

if( !is_object( $weatherState ) ){
	$response->message = "Invalid Weather State";
	exit( json_encode( $response ) );
}

$chatRoomHelper = new Model_Data_ChatRoomProvider();
$chatRoom = $chatRoomHelper->getOneByPk( $chatRoomId );

if( !is_object( $chatRoom ) ){
	$response->message = "Error, unsure of which room";
	exit( json_encode( $response ) );
}

$weatherScheduleHelper = new Model_Data_WeatherScheduleProvider();
$weatherSchedule = new Model_Structure_WeatherSchedule();
$weatherSchedule->setStartTs( time() );
$weatherSchedule->setEndTs( time() + ( $duration * 60 ) ); // duration is in minutes.
$weatherSchedule->setWeatherStateId( $weatherState->getWeatherStateId() );
if( $method == 'room' ){
	$weatherSchedule->setChatRoomId( $chatRoom->getChatRoomId() );
}else{
	$weatherSchedule->setChatRoomTypeId( $chatRoom->getWeatherGroup() );
}


try{
	$arrErrors = array();
	$weatherScheduleHelper->insertOne( $weatherSchedule, $arrErrors );
	if( !empty( $arrErrors ) ){
		$response->message = "Database Error";
		exit( json_encode( $response ) );
	}
}catch( Exception $e ){
	$response->message = "Database Error";
	exit( json_encode( $response ) );
}

$response->message = "Weather set to $state for $duration minutes in room ".$chatRoom->getChatRoomId()."";
$response->success = true;
exit( json_encode( $response ) );
