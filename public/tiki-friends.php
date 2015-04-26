<?php
/**
 * @package tikiwiki
 */
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: tiki-friends.php 46921 2013-07-30 18:20:57Z lphuberdeau $

require_once('tiki-setup.php');

$access->check_user($user);
$access->check_feature('feature_friends');

$smarty->display("tiki-friends.tpl");
