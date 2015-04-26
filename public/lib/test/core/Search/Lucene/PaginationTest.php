<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: PaginationTest.php 46982 2013-08-03 18:29:06Z lphuberdeau $

class Search_Lucene_PaginationTest extends Search_Index_PaginationTest
{
	private $dir;

	function setUp()
	{
		$this->dir = dirname(__FILE__) . '/test_index';
		$this->tearDown();

		$index = new Search_Lucene_Index($this->dir);

		$this->index = $index;
	}

	function tearDown()
	{
		if ($this->index) {
			$this->index->destroy();
		}
	}
}

