<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: messu.php 50610 2014-04-02 11:09:29Z xavidp $

function prefs_messu_list()
{
	return array(
		'messu_mailbox_size' => array(
			'name' => tra('Maximum mailbox size (messages, 0=unlimited)'),
			'description' => tra('Maximum mailbox size (messages, 0=unlimited)'),
			'type' => 'text',
			'size' => '10',
			'filter' => 'digits',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => '0',
		),
		'messu_archive_size' => array(
			'name' => tra('Maximum mail archive size (messages, 0=unlimited)'),
			'description' => tra('Maximum mail archive size (messages, 0=unlimited)'),
			'type' => 'text',
			'size' => '10',
			'filter' => 'digits',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => '200',
		),
		'messu_sent_size' => array(
			'name' => tra('Maximum sent box size (messages, 0=unlimited)'),
			'description' => tra('Maximum sent box size (messages, 0=unlimited)'),
			'type' => 'text',
			'size' => '10',
			'filter' => 'digits',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => '200',
		),
		'messu_truncate_internal_message' => array(
			'name' => tra('Truncate internal message notification to number of characters'),
			'description' => tra('Truncate internal message notification to number of characters'),
			'type' => 'text',
			'size' => '10',
			'filter' => 'digits',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => '2500',
		),		
	);
}
