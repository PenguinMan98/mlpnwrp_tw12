<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Str.php 49195 2013-12-20 15:14:34Z lphuberdeau $

class Math_Formula_Function_Str extends Math_Formula_Function
{
	function evaluate( $element )
	{
		$out = array();

		foreach ( $element as $child ) {
			if ($child instanceof Math_Formula_Element) {
				$out[] = $this->evaluateChild($child);
			} else {
				$out[] = $child;
			}
		}

		return implode(' ', $out);
	}
}

