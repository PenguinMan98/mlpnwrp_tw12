<?php
/*
 * Notes to self:
 * 1) Using the session to store anything specific to one instance of the chat is a bad idea because it will
 * cause complications later for having multiple windows open.
 * */

require_once '../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

// mobile detection
/*echo "(".(file_exists('mobileDetect/Mobile_Detect.php')).")";*/
require_once 'mobileDetect/Mobile_Detect.php';
$_detect = new Mobile_Detect;
$_deviceType = ($_detect->isMobile() ? ($_detect->isTablet() ? 'tablet' : 'phone') : 'computer');

if (empty($_POST['handle'])) // if no username,
{
	//$_SESSION['SYSTEM_MESSAGE'] = "Error, Not Logged in"; // don't bother with an error message.  It's redundant
	header("Location: ../login.php");// send them to login.php.
	die();
}
$handle = $_POST['handle'];

if(!preg_match("/^[\w_-]*$/", $_POST['handle'])){
	$_SESSION['SYSTEM_MESSAGE'] = "Error, invalid username";
	header("Location: ../login.php");// reject a bad username.
	die();
}
if( ( !$userId || empty($userId) ) && $_POST['loggedIn'] ){// if I'm not logged in, but the login form thought I was,
	$_SESSION['SYSTEM_MESSAGE'] = "Your session expired. Please log in again.";
	header("Location: ../login.php");// stale login page.  Send them back.
	die();
}
// ok. Now we have a good user Id and Handle.  Verify them
$characterHelper = new Model_Data_CharacterProvider();
$guestHelper = new Model_Data_GuestUsersProvider();

$myCharacter = $characterHelper->getMyChar($userId, $handle);
if($myCharacter === false){
	$_SESSION['SYSTEM_MESSAGE'] = "Handle in use or reserved.";
	header("Location: ../login.php");// reject a bad username.
	die('not my char');
}

if(!empty($userId) && $userId != 0){
	$ucProvider = new Model_Data_UserChastisementProvider();
	if($ucProvider->is_banned($userId)){
		session_destroy(); // log them ALL the way out
		session_start();
		$_SESSION['SYSTEM_MESSAGE'] = "Your player account has been banned.";
		header("Location: ../login.php");
		die();
	}
	$duration = $ucProvider->is_kicked($userId);
	if($duration){
		session_destroy(); // log them ALL the way out
		session_start();
		$_SESSION['SYSTEM_MESSAGE'] = "Your player account has been kicked for $duration minutes.";
		header("Location: ../login.php");
		die();
	}
}

$characterId = isset($myCharacter['character_id'])? $myCharacter['character_id'] : null;

include_once PUBLIC_ROOT . '/chat/ajax-chat/php/init.php'; /*the main php include file*/

// add the guest character to the database
// or log in the registered character
$arrErrors = array();
if( empty( $myCharacter ) ){  // I'm creating a guest
	$guestUserHelper = new Model_Data_GuestUsersProvider();
	$guestUser = new Model_Structure_GuestUsers( $myCharacter );
	$guestUser->setChatRoomId($current_room['chat_room_id']);
	$guestUser->setHandle($handle);
	$guestUser->setGuestIp($_SERVER['HTTP_X_FORWARDED_FOR']);
	$guestUser->setLastActivity(time());
	if( $userId ) $guestUser->setUserId($userId);
	$guestUserHelper->replaceOne($guestUser, $arrErrors);
	// add the character handle to the temporary player table
	
	// log the login
	$characterLoginLogHelper = new Model_Data_CharacterLoginLogProvider();
	$characterLoginLogEntry = new Model_Structure_CharacterLoginLog();
	$characterLoginLogEntry->setHandle($handle);
	$characterLoginLogEntry->setLoginTime(time());
	$characterLoginLogEntry->setUserIp($_SERVER['HTTP_X_FORWARDED_FOR']);
	if( $userId ) $characterLoginLogEntry->setUserId($userId);
	$characterLoginLogHelper->insertOne($characterLoginLogEntry, $arrErrors);
	
}else{
	$character = new Model_Structure_Character( $myCharacter );
	if( !$character->getLoggedIn() ){
		$character->setLoggedIn( true );
		$character->setLastActivity( time() );
		$character->setChatRoomId( $current_room['chat_room_id'] );
		$characterHelper->updateOne( $character, $arrErrors );
		// tell the database this character is logged in
		
		// log the login
		$characterLoginLogHelper = new Model_Data_CharacterLoginLogProvider();
		$characterLoginLogEntry = new Model_Structure_CharacterLoginLog();
		$characterLoginLogEntry->setHandle($handle);
		$characterLoginLogEntry->setLoginTime(time());
		$characterLoginLogEntry->setUserIp($_SERVER['HTTP_X_FORWARDED_FOR']);
		$characterLoginLogEntry->setUserId($userId);
		$characterLoginLogEntry->setCharacterId($character->getCharacterId());
		$characterLoginLogHelper->insertOne($characterLoginLogEntry, $arrErrors);
	}

}
if(!empty($arrErrors)){
	die(implode('|',$arrErrors));
}

$profilePic = false;
$cutieMark = false;
$chatIcon = false;
if($characterId){
	$profilePic = getImage('profile_pic', $characterId);
	$cutieMark = getImage('cutie_mark', $characterId);
	$chatIcon = getImage('chat_icon', $characterId);
}
if($profilePic){
	// unfinished?
}

$chat_text_color = (is_object($character)) ? $character->getChatTextColor() : "#ffffff";
$chat_name_color = (is_object($character)) ? $character->getChatNameColor() : "#ffffff";


//$chat_logs = array('add' => false, 'get' => false, 'log' => false);// probably won't need this
//$chat_show = array('login' => true, 'guest' => true); // or this
//$chat_path = 'ajax-chat/'; // make everything relative to site_root
?><html>
<head>

<title><?php echo $handle?> - My Little Pony: New Worlds Roleplay Chat!</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="" />
<meta name="keywords"    content="" />

<link href='http://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ROOT?>/chat/ajax-chat/style/style.css" />
<style>
	<?php if($profilePic): ?>
	#character_info_image{
		position: absolute;
		height: 100%;
		width: 100%;
		background:transparent url(../img/<?php echo $characterId?>/<?php echo $profilePic?>) no-repeat scroll top left;
		background-size: 200px;
		opacity: 0.1;
		filter: alpha(opacity=10);
	}
	<?php endif; ?>
</style>

<!-- <script type="text/javascript" src="<?php echo SITE_ROOT?>/js/jquery.js" > </script> -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js" > </script>
<script type="text/javascript" src="<?php echo SITE_ROOT?>/js/jquery.simplemodal.1.4.4.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ROOT?>/chat/ajax-chat/js/cookies.js"></script>

<script type="text/javascript">
var userId 		 = <?php echo ( !$userId || empty($userId) ) ? -1 : $userId ?>;
var room 		 = <?php echo $current_room['chat_room_id']?>; /* for now default this */
var handle 		 = '<?php echo $handle?>';
var ip 			 = '<?php echo $_SERVER['REMOTE_ADDR']?>';
<?php if(is_object($character)): ?>
var chat_name_color = '<?php echo $chat_name_color?>';
var chat_text_color = '<?php echo $chat_text_color?>';
var character_id = <?php echo $characterId?>;
<?php else: ?>
var chat_name_color = 'white';
var chat_text_color = 'white';
var character_id = 'G';
<?php endif; ?>
var guest_char   = <?php echo is_object($guestUser) ? 'true' : 'false'?>;
var forum_login  = <?php echo ($user->data['user_id'] == 'ANONYMOUS') ? 'false' : 'true'; ?>;
var chat_timeout = <?php echo $chat_t_refresh;?>;
var autofocus    = true;
var back_posts   = 50;
var dingOnNew    = false;
var chat_addr    = "<?php echo  $_SERVER['REMOTE_ADDR'] ?>";
var SITE_ROOT	 = "<?php echo SITE_ROOT?>";
var chat_path	 = "<?php echo SITE_ROOT?>/chat/ajax-chat/";
var chatColorOverride = true;
var chatColorOverrideColor = '#ddd';
var profilePic = '<?php echo $profilePic?>';
var cutieMark = '<?php echo $cutieMark?>';
var chatIcon = '<?php echo $chatIcon?>';
var pingOnNew = false;
<?php if(!empty($_SESSION[$handle]['stare_array'])): ?>
var stare_array = <?php echo json_encode($_SESSION[$handle]['stare_array'])?>;
<?php else: ?>
var stare_array = [];
<?php endif; ?>
<?php if(!empty($_SESSION[$handle]['mute_array'])): ?>
var mute_array = <?php echo json_encode($_SESSION[$handle]['mute_array'])?>;
<?php else: ?>
var mute_array = [];
<?php endif; ?>
//variables
var variables = <?php echo (is_object($character) && $character->getVariables() != "") ? json_encode(unserialize($character->getVariables())): '{}'?>;

</script>

</head>
<body class="<?php echo $_deviceType?>">
	<audio id="audio_ding" style="display: none;" controls>
	  <source src="<?php echo SITE_ROOT?>/media/ding.wav" type="audio/wav"></source>
	  <source src="<?php echo SITE_ROOT?>/media/ding.mp3" type="audio/mpeg"></source>
	</audio>
	<div id="page-wrap">

		<div id="top_menu">&nbsp;&nbsp;<a target="_blank" href="/">Home</a>&nbsp;&nbsp;
			|&nbsp;&nbsp;<a target="_blank" href="/chat">+ Character</a>&nbsp;&nbsp;
			|&nbsp;&nbsp;<a target="_blank" href="<?php echo SITE_ROOT?>/Site+Rules">Site Rules</a>&nbsp;&nbsp;
			|&nbsp;&nbsp;<a target="_blank" href="<?php echo SITE_ROOT?>/Chat+Commands">Chat Commands</a>&nbsp;&nbsp;
			|&nbsp;&nbsp;<a href="#" onClick="togglePreferences(this);">Preferences</a>&nbsp;&nbsp;
			<?php if(is_object($character)):?>|&nbsp;&nbsp;<a target="_blank" href="<?php echo SITE_ROOT?>/chat/character/edit.php?c=<?php echo $handle?>">Profile</a>&nbsp;&nbsp;<?php endif;?>
			|&nbsp;&nbsp;<a href="<?php echo SITE_ROOT?>/chat/ajax-chat/php/logout.php?handle=<?php echo $handle?><?php if($characterId) echo "&character_id=$characterId"?>">Logout</a>&nbsp;&nbsp;
		</div>

		<div id="game_notes">
			<h1>Game Notes:</h1>
			<textarea id="game_note_field"></textarea>
		</div>

		<div id="chat"<?php echo ($current_room['chat_room_id'] == 13 || $current_room['chat_room_id'] == 12 || $current_room['chat_room_id'] == 11) ? ' class="game"' : "";?>></div>

		<div id="exit_pm">
			<span id="exit_pm_text"></span>
			<input type="button" onClick="chat_priv_switch('.',true);" value="X">
		</div>
	    <div id="rooms">
			<div class="room" id="room_child">
<?php
$chatRoomHelper = new Model_Data_ChatRoomProvider();
$chatRoomList = $chatRoomHelper->getChatList();

$roomTypeId = 0;
$roomType = "";
foreach ($chatRoomList as $chatRoom) {
	if( $chatRoom['chat_room_type_id'] == 4 && !$isGuide ){ continue; } // only show this room for the guides
	if($roomTypeId != $chatRoom['chat_room_type_id']){
		if($roomTypeId != 0){
			echo "</select><br>";
		}
		$roomTypeId = $chatRoom['chat_room_type_id'];
		$roomType = $chatRoom['type'];
		echo "<label>$roomType</label><br><select onChange=\"room_change( this.value,".(is_object($character)? 'true':'false').", '$handle'); $(this).prop('selectedIndex',0);\">
				<option value=\"\">$roomType</option>";
	}
	//if($chatRoom['chat_room_id'] != $current_room['chat_room_id'])
	echo "<option value=\"".$chatRoom['chat_room_id']."\">".$chatRoom['room_name']."</option>";

} ?>
				</select>
			</div>

	    </div>
        <div id="room_list">
			<div id="header_messages">
				<?php if(file_exists("../img/room1.png")): ?>
				<img src="../img/room1.png">
				<?php else :
					echo $current_room['room_name'];
				endif;?>
			</div>
			<div id="weather" title="The weather is cold and snowy.">
				<img id="weather_img" src="../img/snow_icon.png" />
				<!-- <span id="wwu" style="font-size: 12px;"></span> -->
			</div>
        	<div id="messages"></div>
            <!-- <div id="header_users">Users</div> -->
		    <div id="users">
		      <div class="first"></div>
		      <div id="users_private"></div>
		      <div class="other"></div>
		      <div id="users_this_room"></div>
		      <div id="other_rooms"></div>
		      <div id="users_other"></div>
		    </div>
        </div>

        <div id="form">
        	<form class="send" action="POST" onsubmit="chat_msgs_add(); return false;">
	        	<span id="character_name" style="color: <?php echo $chat_name_color?>"><?php echo $handle?></span>:
	        	<input style="color: <?php echo $chat_text_color?>" id="send" type="text" autocomplete="off" />
        	<?php if(!$blockForm): ?>
	    		<input id="submit_send" class="submit" type="submit" value="Send" />
	    	<?php endif; ?>
	    	</form>
    	</div>
	</div>
	<div id="preferences_container">
		<div id="preferences_box">
			<h3><u>Preferences</u></h3>
			<label>Chat Text Color Override:</label><br>
			&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="chatColorOverride = this.checked;"/><br>
			<label title="Enables and Disables the code that moves the focus to the bottom of the chat when a new post is received.">Autofocus</label><input id="autofocus" checked=true class="input" type="checkbox" onChange="toggleAutofocus(this.checked);" /><br>
			<label>Ping On New</label><input id="pingOnNew" class="input" type="checkbox" onclick="pingOnNew = this.checked;" />
		</div>
	</div>



<?php include_once PUBLIC_ROOT . '/chat/ajax-chat/ajax-chat.php'; /*the main HTML include file*/?>

</body>
</html>
<script type="text/javascript" src="<?php echo SITE_ROOT?>/chat/ajax-chat/js/ajax-chat.js" > </script>
<script>
	$(function(){
		// this is the call that starts it all. Params are: (roomId, isRegistered, $handle)
		chat_api_onload(room, !guest_char, handle);

			/* TAB COMPLETION */
		$('#send').keydown(function( e ){
			if(e.which == 9){
				e.preventDefault();
				var tc_post = $('#send').val();
				var tc_lastWord = tc_post.match(/\w*$/i);
				for (var i in chat_usrs){
					var tc_charName = chat_usrs[i]['name'];
					var tc_regex = '^' + tc_lastWord;
					if( tc_charName.match(new RegExp(tc_regex, 'i')) ){
						var tc_replaceRegex = tc_lastWord + '$';
						$('#send').val( tc_post.replace( new RegExp( tc_replaceRegex, 'i'), tc_charName) );
					}
				}
				return false;
			}
		});

		$('#header_messages').on('click',function(){
			if($('#rooms').css('display')=='block'){
				hideRooms();
			}else{
				showRooms();
			}
		});

		$('#game_note_field').on('keyup', function(){
			var game_notes = $('#game_note_field').val();
			/*console.log('game notes', game_notes, 'room', room);*/
			saveGameNotesSettings(room, game_notes);
		});
		/*$(window).unload( logmeout() );*/

		/*$('#exit_pm').on('click',function(){chat_priv_switch('.',true);});*/
	});

	function toggleAutofocus( state ){
		autofocus = state;
	}

	function logmeout(){
		$.ajax({
			url: chat_path+"php/logout.php",
			data: {handle: handle
				<?php if($characterId): ?>, character_id: <?php echo $characterId?> <?php endif;?>},
			dataType: "JSON"
		});
	}

	</script>