<?php
require_once '../../../application/Core/Bootstrap.php'; // load everything
$_bootstrap = Bootstrap::getInstance();

$charName = $_GET['c'];
$characterProvider = new Model_Data_CharacterProvider();
$character = $characterProvider->getDetailsByCharacterName($charName);

if(empty($character)) {
	// redirect to the character search page
	die("I don't know anypony named $charName!  Sorry! Maybe one will fall from the sky tomorrow?");
}
$profilePic = getImage('profile_pic', $character['character_id']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Profile: <?php echo $character['name']?></title>
    <link rel="stylesheet" href="<?php echo SITE_ROOT?>/css/characterStyle.css">
    <script src="js/jquery.js"></script>
    <!-- <script src="js/script.js"></script> -->
<?php if(!empty($character['profile_css'])):?>
    <style>
    	<?php echo $character['profile_css']?>
    </style>
<?php endif; ?>
  </head>
  <body>
<?php
if(!empty($character['profile_html'])):
	echo $character['profile_html'];
else:
?>
    <div id="siteContainer">
      <div id="site">
        <div id="menuContainer">
        </div>
        <div id="contentContainer" style="width: 100%;">
        	<div id="char_name" style="font-size: 40px; font-weight: bold; width 100%; text-align: center; zindex: 1;"><?php echo $character['name']?></div>
        	<?php if($profilePic): ?>
        	<div id="char_img"><img src="../../img/<?php echo $character['character_id']?>/<?php echo $profilePic?>" /></div>
        	<?php endif; ?>
        	<p>Player: <a href="../../chat/player/characters.php?p=<?php echo $character['username']?>"><?php echo $character['username']?></a><br>
        	Race: <?php echo $character['race']?> <?php if($character['character_race_note']) echo "( ". $character['character_race_note'] ." )";?><br>
        	Gender: <?php echo $character['gender']?><br>
        	Age: <?php echo $character['age']?></p>
        	<p>Status: <?php echo $character['status']?></p>
        	<p>Biography: <?php echo $character['bio']?></p>
        	<p>Notes: <?php echo $character['player_notes']?></p>
        </div>
        <div id="footerContainer">
        </div>
      </div>
    </div>
<?php endif; ?>
  </body>
</html>
