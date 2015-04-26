<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: PlainShortText.php 46796 2013-07-23 16:59:59Z lphuberdeau $

class Search_Type_PlainShortText implements Search_Type_Interface
{
	private $value;

	function __construct($value)
	{
		$this->value = $value;
	}

	function getValue()
	{
		return $this->value;
	}
}
