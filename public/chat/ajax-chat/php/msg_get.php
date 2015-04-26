<?php
// Copyright (C) 2013 Pengy Programming. All rights reserved.

// must bootstrap the ajax calls
require_once '../../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

$response = new stdClass();
$response->errors = array();

//--------------SECURITY---------------
// verify login
$handle = htmlentities(preg_replace("/\\s+/iX", " ", $_GET['user']), ENT_QUOTES);
$character = null;
if($userId) { // logged in + registered char
	$characterHelper = new Model_Data_CharacterProvider();
	$character = $characterHelper->getOneByCharacterName($handle);
}
if(!$userId || !is_object($character)){ // guest
	$guestUserHelper = new Model_Data_GuestUsersProvider();
	$guestUser = $guestUserHelper->getOneByPk($handle);
}
if(is_object($character)){
	$character->setLastStatusRequest(time());
	$characterHelper->updateOne($character, $response->errors);
}elseif(is_object($guestUser)){
	$guestUser->setLastStatusRequest(time());
	$guestUserHelper->updateOne($guestUser, $response->errors);
}
if((!is_object($character) && !is_object($guestUser)) || // no character match or
		(is_object($character) && !$character->getLoggedIn()) ){ // registered character isn't logged in
	$response->success = false;
	$response->error = "Character Not Logged In";
	echo json_encode($response);
	die();
}

//---------------/SECURITY-----------------

$response->success = true;
$response->text = "";
$response->lines = array();

// check for a kick/ban/mute
$ucProvider = new Model_Data_UserChastisementProvider();
$gold_boot = $ucProvider->is_chastised($userId);
if($gold_boot){
	$response->success = false;
	$response->error = "Error: Insufficient Privilege.";
	die(json_encode($response));
}

if (isset($_GET['room']) &&
	    isset($_GET['user']) &&
	    /*isset($_GET['pass']) &&*/
	    isset($_GET['mptr'])){ /* to get messages, you must be logged in and in a room and tell me the last message you got */
	include_once 'init.php'; /*Get the INIT*/

	$requestId = $_GET['rand'];
	$roomId = $_GET['room'];
	$handle = $_GET['user'];
	$lastPostId = $_GET['mptr'];
	$lastWeatherUpdate = intval($_REQUEST['chatState']['lastWeatherUpdate']);
	$currentWeatherStateId = intval($_REQUEST['chatState']['weatherStateId']);
	
	// check for a valid room
	if(is_numeric($roomId) && $roomId > 0){
		if(is_object($character) && $character->getChatRoomId() != $roomId )
		{
			if($character->getChatRoomId() == 27){ // if they are not in the admin room in their client, but they are in the db
				$response->toAdminRoom = 27;
			}elseif($roomId == 27){ // if they are not in the admin room in the db, but they are in the client
				$response->toAdminRoom = 1;
			}else{
				$response->success = false;
				$response->error = "You aren't in that room.";
				die(json_encode($response));
			}
		}
		if(is_object($guestUser) && $guestUser->getChatRoomId() != $roomId )
		{
			if($guestUser->getChatRoomId() == 27){ // if they are not in the admin room in their client, but they are in the db
				$response->toAdminRoom = 27;
			}elseif($roomId == 27){ // if they are not in the admin room in the db, but they are in the client
				$response->toAdminRoom = 1;
			}else{
				$response->success = false;
				$response->error = "You aren't in that room.";
				die(json_encode($response));
			}
		}
	}else{
		$response->success = false;
		$response->error = "You aren't in any room.";
		die(json_encode($response));
	}
	

	  // log all calls to this file
	  /*$logProvider = new Model_Data_LogProvider();
	  $log = new Model_Structure_Log(array(
	  		'file'=>__FILE__,
	  		'log_entry'=>serialize($_REQUEST),
	  		'severity'=>'notice'
	  ));
	  $arrErrors = array();
	  $logProvider->insertOne($log, $arrErrors);*/

	$modified = unlog_users(); /* refresh the user list and note if we changed it */

	try{
		$cm = new CacheManager();
		
		//--------------------ROOM--------------------
		$chatRoom = null;
		$response->request = $_REQUEST;
		$response->room = array();
		if($roomId == 13 || $roomId == 12 || $roomId == 11){ // not all rooms need this much data.
			$chatRoomProvider = new Model_Data_ChatRoomProvider();
			$chatRoom = $chatRoomProvider->getOneByPk($roomId);
			$response->room = $chatRoom->getAsArray();
		}
		//--------------------WWU--------------------
		$response->wwu_count = 0;
		if( $roomId > 0 && $roomId < 8 ){ 
			$etcCache = $cm->getEtcCache();
			$response->wwu_count = $etcCache['wwu_count'];
		}
		
		//--------------------WEATHER-------------------
	  	if( ( time() % 5 ) == 0 ){ // run this check every five seconds?
	  		$response->weather_debug = "I ran.  $lastWeatherUpdate < ".(time() - 5)."|".( $lastWeatherUpdate < ( time() - 5 ) )."|";
			$weatherScheduleHelper = new Model_Data_WeatherScheduleProvider();
			$weatherStateHelper = new Model_Data_WeatherStateProvider();
			$newWeatherState = array();

			$scheduledWeather = $weatherScheduleHelper->getByRoomId($roomId);
			if(!is_object($scheduledWeather) && $currentWeatherStateId != 1){ // no scheduled weather and we're not at clear,
				$defaultWeatherState = $weatherStateHelper->getOneByPk( 1 ); // default to clear
				$newWeatherState = $defaultWeatherState->getAsArray();
			}elseif(is_object($scheduledWeather) && $currentWeatherStateId != $scheduledWeather->getWeatherStateId()){ // scheduled weather and we aren't there
				$scheduledWeatherState = $weatherStateHelper->getOneByPk( $scheduledWeather->getWeatherStateId() );
				$newWeatherState = $scheduledWeatherState->getAsArray();
			}
			$response->newWeatherState = $newWeatherState;
	  	}

	  	//--------------------POSTS--------------------
	  	
		$lines = $cm->getPostsByUsernameRoomAndLastPost($handle, $roomId, $lastPostId);
		//$tempLines = $cm->getPostsByUsernameRoomAndLastPost($handle, $roomId, $lastPostId);
		if($lines === false){
			$response->debug = "pulled from database";
			$chatLogHelper = new Model_Data_ChatLogProvider();
			$lines = $chatLogHelper->getPosts($roomId, $handle, $lastPostId, $chat_histlen, $registered);
		}else{
			$response->debug = "pulled from cache";
		}
		$response->lines = $lines;
		

		foreach($response->lines as &$line){
		  	$postDate = new DateTime();
		  	$postDate->setTimestamp($line['timestamp']);
		  	$line['formattedDate'] = $postDate->format('H:i:s');
		  	$twentyThreeHour = (floor($line['timestamp']/3600))%23; // convert timestamp to hours past epoch (floor(x/3600)) , then mod 23 to get the 23-hour hour
		  	$line['twentyThreeHour'] = (($twentyThreeHour < 10)? '0'.$twentyThreeHour : $twentyThreeHour ) . ":" . $postDate->format('i:s');
		  	$adjustedHour = mktime(
		  			date('H',$line['timestamp'])+2,
		  			date("i",$line['timestamp']),
		  			date("s",$line['timestamp']),
		  			date("n",$line['timestamp']),
		  			date("j",$line['timestamp']),
		  			date("Y",$line['timestamp']));
		  	$line['adjustedHour'] = date('h:i:s A',$adjustedHour);
		  	$line['interval'] = (time()-$postDate->format('U'));
		}
	}catch(Exception $e){
	  	//$response->operator = "-"; //return a -    May be obsolete
	  	$response->success = false;
	  	$response->error = $e->getMessage();
	}
}
$response->serverTime = time();
$encodedResponse = json_encode($response);
echo $encodedResponse;

?>
