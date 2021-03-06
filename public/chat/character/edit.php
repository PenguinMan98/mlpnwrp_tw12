<?php
require_once '../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

// this operation requires the user to be logged in
if(empty($_SESSION['u_info'])){
	header('Location: ' . SITE_ROOT . '/tiki-login_scr.php');
}

$charName = $_GET['c'];
$characterProvider = new Model_Data_CharacterProvider();
$character = $characterProvider->getDetailsByCharacterName($charName);

if(empty($character)) {
	// redirect to the character search page
	die("I don't know anypony named $charName!  Sorry! Maybe one will fall from the sky tomorrow?");
}

$userProvider = new Model_Data_UsersUsersProvider();
//$userId = $character['user_id'];
if(!$userProvider->verifyUserAndCharacterId($userId, $character['character_id'])){
	die("This is not your character.");
}

$characterRaceProvider = new Model_Data_CharacterRaceProvider();
$characterAgeProvider = new Model_Data_CharacterAgeProvider();
$raceList = $characterRaceProvider->getRaceList();
$ageList = $characterAgeProvider->getAgeList();

$basic_race = false;
foreach($raceList as $rl){
	if($rl->getName() == $character['race']){
		$basic_race = true;
	}
}

$profilePic = getImage('profile_pic', $character['character_id']);
$cutieMark = getImage('cutie_mark', $character['character_id']);
$chatIcon = getImage('chat_icon', $character['character_id']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Character</title>
    <link rel="stylesheet" href="<?php echo SITE_ROOT?>/css/characterStyle.css">
	<link href="<?php echo SITE_ROOT?>/css/le-frog/jquery-ui-1.10.2.custom.css" rel="stylesheet">
	<script src="<?php echo SITE_ROOT?>/js/jquery-1.9.1.js"></script>
	<script src="<?php echo SITE_ROOT?>/js/jquery-ui-1.10.2.custom.js"></script>
	<link href="<?php echo SITE_ROOT?>/js/spectrum/spectrum.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo SITE_ROOT?>/js/spectrum/spectrum.js"></script>
	<style>
	.post_sample_1 {color: <?php echo $character['chat_name_color']?>}
	.post_sample_2 {color: <?php echo $character['chat_text_color']?>}
	label{
		display: inline-block;
		width: 200px;
	}
	</style>
  </head>
  <body>
    <div id="siteContainer">
      <div id="site">
        <div id="headingContainer">
        </div>
        <div id="menuContainer">
        </div>
        <div id="contentContainer">
        	<h1>Edit <?php echo $character['name']?></h1>
        	<div id="system_messages">
        		<?PHP
        			if(!empty($_SESSION['system_messages'])){
						foreach($_SESSION['system_messages'] as $msg){
							echo '<p class="error">'.$msg.'</p>';
						}
						unset($_SESSION['system_messages']);
					}
				?>
        	</div>
			<div id="tabs">
				<ul>
					<li><a href="#basics">The Basics</a></li>
					<li><a href="#texts">Texts</a></li>
					<li><a href="#images">Images</a></li>
					<li><a href="#html">HTML</a></li>
				</ul>
				<form id="characterForm" enctype='multipart/form-data' method="POST" action="<?php echo SITE_ROOT?>/chat/character/save.php">
					<div id="basics">
						<label>Character Name:</label>
						<input type="hidden" name="character_name" value="<?php echo $character['name']?>" />
						<?php echo $character['name']?><br>

						<!-- <label>Formatted Name:</label>
						<input type="text" name="character_formatted_name" id="character_formatted_name" required><br> -->

						<label>Gender:</label>
						<input type="text" name="gender" id="gender" value="<?php echo $character['gender']?>"><br>

						<label>Race</label>
						<select name="race">
							<?php if(!$basic_race): ?>
								<option value="<?php echo $character['character_race_id']?>"><?php echo $character['race']?></option>
							<?php endif; ?>
							<?php foreach ($raceList as $race): ?>
							<option
								title="<?php echo $race->getDescription()?>"
								value="<?php echo $race->getCharacterRaceId()?>"
								<?php echo ($character['character_race_id'] == $race->getCharacterRaceId()) ? 'selected' : ''?>
								><?php echo $race->getName()?></option>
							<?php endforeach; ?>
						</select><br>

						<label>Age</label>
						<select name="age" id="age">
							<?php foreach ($ageList as $age): ?>
							<option
								title="<?php echo $age->getDescription()?>"
								value="<?php echo $age->getCharacterAgeId()?>"
								<?php echo ($character['character_age_id'] == $age->getCharacterAgeId()) ? 'selected' : ''?>
								><?php echo $age->getName()?>
								</option>
							<?php endforeach; ?>
						</select><br>
						<br>
						<input type="submit" name="edit" value="Update">
						<hr>
						<label>Default Chat Colors:</label>
						<input type="text" id="chat_name_color" name="chat_name_color" size="7" value="<?php echo $character['chat_name_color']?>">
						<input type="text" id="chat_text_color" name="chat_text_color" size="7" value="<?php echo $character['chat_text_color']?>"><br>
						<p style="background-color: #000; color: #fff;"><span class="post_sample_1">Character</span>: <span class="post_sample_2">"I say something fun!"</span><br>
						: <span class="post_sample_1">Character does something fun!</span></p>
						<br>
						<input type="submit" name="edit" value="Update">
					</div>
					<div id="texts">
						<label>Status</label>
						<textarea id="status" name="status" rows="1" cols="80" title="Status was designed for temporary changes to your character or announcements of recent changes.  It is shown when you mouseover a character in the chat menu."><?php echo $character['status']?></textarea><br>

						<label>Bio</label>
						<textarea id="bio" name="bio" rows="5" cols="80" title="Bio is for backstory and more permanent changes to your character" placeholder="This is where you enter the biography"><?php echo $character['bio']?></textarea><br>

						<label>Player Notes</label>
						<textarea id="player_notes" name="player_notes" rows="5" cols="80" title="Player notes was designed for players to enter OOC details about their character." placeholder="Public Character Notes"><?php echo $character['player_notes']?></textarea><br>

						<label>Player Private Notes</label>
						<textarea id="player_private_notes" name="player_private_notes" rows="5" cols="80" title="Private player notes was designed for players to enter any details about their character that they don't want made public." placeholder="Private Character Notes"><?php echo $character['player_private_notes']?></textarea><br>
						<br>
						<input type="submit" name="edit" value="Update">
					</div>
					<div id="images">
						<label>Profile Image</label> (Max Filesize = 80Kb)<br>
						<?php echo ($profilePic) ? '<img src="../../img/' . $character['character_id'] . '/' . $profilePic . '" /><br>' : '' ?>
						<input type="file" name="profile_image"><br>
						<hr>
						<label>Cutie Mark</label> (Pixel Dimensions: 15px X 15px)<br>
						<?php echo ($cutieMark) ? '<img src="../../img/' . $character['character_id'] . '/' . $cutieMark . '" /><br>' : '' ?>
						<input type="file" name="cutie_mark"><br>
						<hr>
						<label>Chat Icon</label> (Pixel Dimensions: 50px X 25px)<br>
						<?php echo ($chatIcon) ? '<img src="../../img/' . $character['character_id'] . '/' . $chatIcon . '" /><br>' : '' ?>
						<input type="file" name="chat_icon"><br>
						<br>
						<input type="submit" name="edit" value="Update">
					</div>
					<div id="html">
						<label>Profile HTML</label> (This is used instead of the default layout if you fill it in)<br>
						<textarea id="profile_html" name="profile_html" rows="8" cols="40" title=""><?php echo $character['profile_html']?></textarea><br>

						<label>Profile CSS</label> <br>
						<textarea id="profile_css" name="profile_css" rows="8" cols="40" title=""><?php echo $character['profile_css']?></textarea><br>
						<br>
						<input type="submit" name="edit" value="Update">
					</div>
				</form>
			</div>
        </div>
        <div id="footerContainer">
        </div>
      </div>
    </div>
  </body>
<script type="text/javascript">
	$(function() {
		$( "#tabs" ).tabs();
	});

	$('#chat_name_color').spectrum({
		color: "<?php echo $character['chat_name_color']?>",
		preferredFormat: "hex6",
		showInput: true,
		clickoutFiresChange: true,
		showInitial: true,
		chooseText: "Pick Me!",
		cancelText: "I liked the old one better.",
		move: function(hex) {
			var samples = $('.post_sample_1');
			for(var i=0; i < samples.length; i++){
				samples[i].style.color = hex;
			};
		}
	});
	$('#chat_text_color').spectrum({
		color: "<?php echo $character['chat_text_color']?>",
		preferredFormat: "hex6",
		showInput: true,
		clickoutFiresChange: true,
		showInitial: true,
		chooseText: "Pick Me!",
		cancelText: "I liked the old one better.",
		move: function(hex) {
			var samples = $('.post_sample_2');
			for(var i=0; i < samples.length; i++){
				samples[i].style.color = hex;
			};
		}
	});
	</script>
</html>
