<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: modifier.avatarize.php 47701 2013-09-24 12:54:23Z jonnybradley $

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     capitalize
 * Purpose:  capitalize words in the string
 * -------------------------------------------------------------
 */
function smarty_modifier_avatarize($user, $float = '')
{
	$avatar = TikiLib::lib('tiki')->get_user_avatar($user, $float);
	if ( $avatar != '') {
		$avatar = TikiLib::lib('user')->build_userinfo_tag($user, $avatar);
	}
	return $avatar;	
}

