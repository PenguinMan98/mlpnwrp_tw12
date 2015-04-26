<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Chain.php 46592 2013-07-08 15:06:13Z lphuberdeau $

class Tiki_Event_Chain implements Tiki_Event_EdgeProvider
{
	private $event;
	private $manager;

	function __construct(Tiki_Event_Manager $manager, $eventName)
	{
		$this->event = $eventName;
		$this->manager = $manager;
	}

	function __invoke($arguments, $eventName, $priority)
	{
		$this->manager->internalTrigger($this->event, $arguments, $priority, $eventName);
	}

	function getTargetEvents()
	{
		return array($this->event);
	}
}
