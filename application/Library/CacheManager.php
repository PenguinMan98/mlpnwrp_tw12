<?php 

class CacheManager{
	var $postCache = array();
	var $ARR_MAX = 50;
	var $BACKDATE_THRESHHOLD = 0;// one week ago?
	var $etcCache = array();
	var $lastPostId = 0;
	var $debug = "";
	var $LOCKFILE_EXPIRE = 20;
	
	public function __construct(){
		// open and parse the cache file
		$cacheFileFound = false;
		$cacheError = '';
		try{
			// make the cache dir. if it's not there.
			if(!dir(APP_ROOT . 'Cache/')){
				mkdir(APP_ROOT . 'Cache/', 0755, true);
			}
			
			if(file_exists(APP_ROOT . 'Cache/PostCache.php')){
				$cacheFileFound = true;
				$this->postCache = unserialize(file_get_contents(APP_ROOT . 'Cache/PostCache.php')); // use it.
			}else{
				$this->postCache = array(); // build a new one
			}
		}catch(Exception $e){
			$cacheError = $e->getMessage();
		}

		$this->lastPostId = isset($this->postCache['lastPostId']) ? $this->postCache['lastPostId'] : 0;
		
		//$this->debug = "Cache Object Created.";
		if(!$cacheFileFound){
			$this->debug .= " Cache File NOT Found.";
		}
		if($cacheError != ''){
			$this->debug .= " Cache Error happened: " . $cacheError;
		}
		
		if(!$cacheFileFound || $cacheError != ''){
			// logging
			$arrErrors = array();
			$logHelper = new Model_Data_LogProvider();
			$newLog = new Model_Structure_Log(array(
					'file' => __FILE__,
					'log_entry' => $this->debug,
					'severity' => 3
			));
			//$logHelper->insertOne($newLog, $arrErrors);
		}
		
	}
	
	public function buildCache(){
		$this->BACKDATE_THRESHHOLD = time() - 60*60*24*7;// one week ago?
		$skipMe = false;
		$this->debug .= "LPI: " . $this->lastPostId . "";
		//---------lockfile---------
		try{
			$lockFilename = APP_ROOT . 'Cache/CacheLock.php';
			// remove a stale lockfile
			if(file_exists($lockFilename)){
				$lockAge = filemtime($lockFilename);
				if( time() - $lockAge > $this->LOCKFILE_EXPIRE ){
					$this->debug .=  " Lock Killed";
					unlink($lockFilename);
				}
			}
			// check for lockfile
			if(file_exists($lockFilename)){
				$lockAge = filemtime($lockFilename);
				$this->debug .=  " Skip";
				$skipMe = true;
				//return true; // cache is busy
			}else{
				$this->debug .=  " Lock";
				file_put_contents( $lockFilename, "Cache Locked", LOCK_EX); // create a lock file
			}
		}catch(Exception $e){
			echo $e->getMessage();
			print_r($e);
			die();
		}
		
		if(!$skipMe){
			//echo "Select * from chat_log where chat_log_id > $this->lastPostId<br>";
			$chatLogProvider = new Model_Data_ChatLogProvider();
			$newPosts = $chatLogProvider->getAllPostsAfterId($this->lastPostId); // new array of new posts
			//$newPosts = array(); // no new posts
			if(count($newPosts) > 0)
				$this->debug .= " " . count($newPosts) . " posts";
			
			// build postarrays
			foreach($newPosts as $line){ // for each new post
				if($line->getChatLogTypeId() == 1){ // public
					$this->updateRoomCache($line->getChatRoomId(), $line);
					//$this->debug .= " Public: " . $line->getChatLogId() . ".";
					
				}elseif($line->getChatLogTypeId() == 2){ // private
						// add it to the senders cache
					$this->updatePrivateCache( $line->getHandle(), $line );
						// add it to the recipients cache
					$this->updatePrivateCache( $line->getRecipientUsername(), $line );
					
					//$this->debug .= " Private: " . $line->getChatLogId() . ".";
				}else{ // to be handled later
					//--------
				}
				
				$this->lastPostId = $line->getChatLogId();
				
				/*echo "line {$this->postCache['lastPostId']}<br>";
				print_r($line);*/
			}
			$this->postCache['lastPostId'] = $this->lastPostId;
			$this->debug .= " NLPI " . $this->postCache['lastPostId'];
			
			// generate files
			foreach( $this->postCache as $roomId => $publicRoom){
				$publicPosts = !empty($publicRoom['last50']) ? $publicRoom['last50'] : array();
				$cacheFileName = "room_{$roomId}_cache.php";
				file_put_contents( APP_ROOT . 'Cache/' . $cacheFileName, serialize( $publicPosts ), LOCK_EX );
			}
			
			file_put_contents(APP_ROOT . 'Cache/PostCache.php', serialize($this->postCache), LOCK_EX);
			
			// --------ETC CACHE--------------
			$this->etcCache['wwu_count'] = $chatLogProvider->wwuPostCount();
			
			file_put_contents(APP_ROOT . 'Cache/EtcCache.php', serialize($this->etcCache), LOCK_EX);
			
			// remove the lockfile
			unlink(APP_ROOT . 'Cache/CacheLock.php');
		}
		
		if(count($newPosts) > 2 || $skipMe){
			// logging
			$arrErrors = array();
			$logHelper = new Model_Data_LogProvider();
			$newLog = new Model_Structure_Log(array(
					'file' => __FILE__,
					'log_entry' => $this->debug,
					'severity' => 3
			));
			//$logHelper->insertOne($newLog, $arrErrors);
		}
	}
	
	public function fixCache(){
	
		//---------lockfile---------
		try{
			if(file_exists(APP_ROOT . 'Cache/CacheLock.php')){
				return false; // cache is busy
			}else{
				file_put_contents(APP_ROOT . 'Cache/CacheLock.php', "Cache Locked", LOCK_EX); // create a lock file
			}
		}catch(Exception $e){
			echo $e->getMessage();
			print_r($e);
			die();
		}
		
		$this->debug .= " Fix LPI " . $this->lastPostId;
		
		// get the posts from room 1
		$room1posts = $this->getPublicRoomById( 1 );
		$nlpi = 0;
		
		foreach($room1posts as $post){
			$nlpi = ($this->postCache['lastPostId'] > $post['chat_log_id']) ? $this->postCache['lastPostId'] : $post['chat_log_id'];
		}
			
		// generate file only if there is a change
		if($nlpi > $this->lastPostId){
			
			$this->postCache['lastPostId'] = $nlpi;
			$this->lastPostId = $nlpi;
			$this->debug .= " NLPI " . $nlpi;
			
			file_put_contents(APP_ROOT . 'Cache/PostCache.php', serialize($this->postCache), LOCK_EX );
		}		
				
		// remove the lockfile
		unlink(APP_ROOT . 'Cache/CacheLock.php');
		
		// logging
		$arrErrors = array();
		$logHelper = new Model_Data_LogProvider();
		$newLog = new Model_Structure_Log(array(
				'file' => __FILE__,
				'log_entry' => $this->debug,
				'severity' => 3
		));
		//$logHelper->insertOne($newLog, $arrErrors);
	}
	
	function getPublicRoomById( $roomId ){
		if(isset($this->postCache)
				&& isset($this->postCache[$roomId])
				&& isset($this->postCache[$roomId]['last50']))
			return $this->postCache[$roomId]['last50'];
		return array();  // room not found
	}

	function updateRoomCache( $roomId, $line ){
		$fileName = "Cache/room_{$roomId}_cache.php";
		
		// import the cache
		try{
			if(file_exists(APP_ROOT . $fileName))
				$posts = unserialize(file_get_contents(APP_ROOT . $fileName));
			else{
				$posts = array();
			}
		}catch(Exception $e){
			echo $e->getMessage();
			print_r($e);
			die();
		}
		
		// add this one to the line array
		$posts[$line->getChatLogId()] = $line->getAsArray();
		if( count( $posts ) > $this->ARR_MAX ){ // if greater than max
			$temp = array_reverse($posts, true); // reverse the array
			while(count($temp) > $this->ARR_MAX){
				array_pop($temp); // pop off the end. This preserves keys.
			}
			file_put_contents( APP_ROOT . $fileName, serialize( array_reverse( $temp, true ) ), LOCK_EX ); // put the results back into the file
		}else{ // not too big,
			file_put_contents( APP_ROOT . $fileName, serialize( $posts ), LOCK_EX ); // put the results in the file
		}
		
	}
	
	function updatePrivateCache( $characterName, $line ){
		$fileName = "Cache/priv_{$characterName}_cache.php";
		try{
			if(file_exists(APP_ROOT . $fileName))
				$posts = unserialize(file_get_contents(APP_ROOT . $fileName));
			else{
				$posts = array();
			}
		}catch(Exception $e){
			echo $e->getMessage();
			print_r($e);
			die();
		}
		
		$posts[$line->getChatLogId()] = $line->getAsArray();
		if( count( $posts ) > $this->ARR_MAX ){
			$temp = array_reverse($posts, true);
			while(count($temp) > $this->ARR_MAX){
				array_pop($temp);
			}
			file_put_contents( APP_ROOT . $fileName, serialize( array_reverse( $temp, true ) ), LOCK_EX );
		}else{
			file_put_contents( APP_ROOT . $fileName, serialize( $posts ), LOCK_EX );
		}
		
	}
	
	function getPublicPostsByRoomId( $roomId ){ // this one pulls from a cachefile
		$fileName = "Cache/room_{$roomId}_cache.php";
		try{
			if(file_exists(APP_ROOT . $fileName))
				$posts = unserialize(file_get_contents(APP_ROOT . $fileName));
			else{
				$posts = array();
			}
		}catch(Exception $e){
			echo $e->getMessage();
			print_r($e);
			die();
		}
		return $posts;
	}
	
	function getPrivatePostsByUsername( $userName ){ // this one pulls from a cachefile
		$fileName = "Cache/priv_{$userName}_cache.php";
		try{
			if(file_exists(APP_ROOT . $fileName))
				$privatePosts = unserialize(file_get_contents(APP_ROOT . $fileName));
			else{
				$privatePosts = array();
			}
		}catch(Exception $e){
			//echo $e->getMessage();
			return array();// silently fail
		}
		return $privatePosts;
	}
	
	/*
	 * The cache version of getPosts()
	 * */
	function getPostsByUsernameRoomAndLastPost( $username, $roomId, $lastPostId = 0 ){
		$retVal = null;
		$postCount = 0;
		if($lastPostId > $this->lastPostId){  // if the postid I'm asking for is newer than the one I have in this object, then there is a problem.
			$this->debug .= " PLPI: $lastPostId, CLPI: {$this->lastPostId}.";
			$this->fixCache();
			return false;
		}else{
			$publicPosts = $this->getPublicPostsByRoomId( $roomId );
			$privatePosts = $this->getPrivatePostsByUsername( $username );
			$combinedPostArr = $publicPosts + $privatePosts;
			$responseArr = array();
			$idArr = array();
			foreach($combinedPostArr as $post){
				if($post['chat_log_id'] > $lastPostId )
					$idArr[] = $post['chat_log_id'];
			}
			asort($idArr);
			
			foreach($idArr as $id){
				$responseArr[] = $combinedPostArr[$id];
				$this->lastPostId = $id;
			}
			$retVal = $responseArr;
			$this->debug .= " id's: " . implode('|', $idArr) . ".";
		}
		
		// logging
		if(count($responseArr) > 2){
			$arrErrors = array();
			$logHelper = new Model_Data_LogProvider();
			$lastLog = $logHelper->getLastLog();
			if($lastLog->getFile() != __FILE__ || $lastLog->getLogEntry() != $this->debug ){
				$newLog = new Model_Structure_Log(array(
						'file' => __FILE__,
						'log_entry' => $this->debug,
						'severity' => 3
				));
				//$logHelper->insertOne($newLog, $arrErrors);
			}
		}
		
		return $retVal;
	}
	
	function getEtcCache(){ // this one pulls from the etc cachefile
		$fileName = "Cache/EtcCache.php";
		try{
			if(file_exists(APP_ROOT . $fileName))
				return unserialize(file_get_contents(APP_ROOT . $fileName));
			else{
				return array();
			}
		}catch(Exception $e){
			//echo $e->getMessage();
			return array();// silently fail
		}
	}
	
}