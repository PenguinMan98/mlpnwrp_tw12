<?php 

//die('I\'m sorry, but registration is temporarily disabled.');

require_once ('tiki-setup.php');

$_SESSION['nwrp_security_token'] = true;

header("Location: tiki-register.php");
