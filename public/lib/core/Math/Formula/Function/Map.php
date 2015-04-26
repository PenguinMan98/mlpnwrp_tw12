<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Map.php 46558 2013-07-05 17:50:18Z lphuberdeau $

class Math_Formula_Function_Map extends Math_Formula_Function
{
	function evaluate( $element )
	{
		$out = array();

		foreach ( $element as $child ) {
			$out[$child->getType()] = $this->evaluateChild($child[0]);
		}

		return $out;
	}
}

