<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: ResultSet.php 48778 2013-11-27 18:11:51Z lphuberdeau $

class Search_Elastic_ResultSet extends Search_ResultSet
{
	function highlight($content)
	{
		return strip_tags($content['_highlight'], '<em>');
	}
}

