<?php
include("../Core/Bootstrap.php");
$_bootstrap = Bootstrap::getInstance();

echo "Post Cron!";

$ARR_MAX = 50; // extend this to 50
$BACKDATE_THRESHHOLD = time() - 60*60*24*7;// one week ago?

//$cacheFile = simplexml_load_file('../Cache/PostCache.php');
try{
	if(file_exists('../Cache/PostCache.php'))
		$postCache = unserialize(file_get_contents('../Cache/PostCache.php'));
	else{
		$postCache = array();
	}
}catch(Exception $e){
	echo $e->getMessage();
	print_r($e);
	die();
}

//include("../Cache/PostCache.php");

$lastPostId = isset($postCache['lastPostId']) ? $postCache['lastPostId'] : 0;
//echo "Select * from chat_log where chat_log_id > $lastPostId<br>";
$chatLogProvider = new Model_Data_ChatLogProvider();
$newPosts = $chatLogProvider->getAllPostsAfterId($lastPostId);
//$newPosts = array(); // no new posts

// generate file

foreach($newPosts as $line){ // for each new post
	if($line->getChatLogTypeId() == 1){ // public
		$posts = getPublicRoomById( $line->getChatRoomId() );
		if(!$posts){ $posts = array(); }
		$posts[$line->getChatLogId()] = $line;
		$temp = array_reverse($posts, true);
		while(count($temp) > $ARR_MAX){
			array_pop($temp);
		}
		$postCache['public'][$line->getChatRoomId()]['last50'] = array_reverse($temp, true);
	}elseif($line->getChatLogTypeId() == 2){ // private
		$posts = getPrivateByRecipientId( $line->getRecipientUserId() );
		if(!$posts){ $posts = array(); }
		$posts[$line->getChatLogId()] = $line;
		$temp = array_reverse($posts, true);
		while(count($temp) > $ARR_MAX){
			array_pop($temp);
		}
		$postCache['private'][$line->getRecipientUserId()]['last50'] = array_reverse($temp, true);
	}else{ // to be handled later
		//--------
	}
	$postCache['lastPostId'] = $line->getChatLogId();
}
echo $postCache['lastPostId'];
file_put_contents("../Cache/PostCache.php", serialize($postCache));

// ================Functions===============

function getPublicRoomById( $roomId ){
	GLOBAL $postCache;
	
	if(isset($postCache['public'])
			&& isset($postCache['public'][$roomId])
			&& isset($postCache['public'][$roomId]['last50']))
		return $postCache['public'][$roomId]['last50'];
	return false;  // room not found
}

function getPrivateByRecipientId( $recipientId ){
	GLOBAL $postCache;

	if(isset($postCache['private'])
			&& isset($postCache['private'][$recipientId])
			&& isset($postCache['private'][$recipientId]['last50']))
		return $postCache['private'][$recipientId]['last50'];
	return false;  // room not found
}


