<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Random.php 49834 2014-02-11 22:13:14Z arildb $

class Math_Formula_Function_Random extends Math_Formula_Function
{
	function evaluate( $element )
	{
		$range = array(0, 9999);

		foreach ( $element as $child ) {
			$range[] = $this->evaluateChild($child);
		}

		return rand(min($range), max($range));
	}
}

