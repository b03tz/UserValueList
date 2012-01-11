<?php
/**
 * userValueList
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of userValueList.
 *
 * userValueList is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * userValueList is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * userValueList; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package userValueList
 */

// Load the userValueList class
$uservaluelist = $modx->getService('uservaluelist','userValueList',$modx->getOption('uservaluelist.core_path',null,$modx->getOption('core_path').'components/uservaluelist/').'model/uservaluelist/',$scriptProperties);
if (!($uservaluelist instanceof userValueList)) return '';

// Receive properties
$key = $modx->getOption('key', $scriptProperties, 'userValueList');
$addKey = $modx->getOption('addKey', $scriptProperties, 'ulv_list');
$value = $modx->getOption('value', $scriptProperties, $modx->resource->get('id'));
$addTpl = $modx->getOption('addTpl', $scriptProperties, 'uvl.addTpl');
$removeTpl = $modx->getOption('removeTpl', $scriptProperties, 'uvl.removeTpl');

if ($value == '') {
	$value = $modx->resource->get('id');
}

// Get current value
if ($uservaluelist->isLoggedIn()) {
	// Check if value needs to be added
	$uservaluelist->checkListValue($key, $addKey, $value); 

	// Get the extra fields
	$currentValues = $uservaluelist->getUserListValue($key);
} else { 
	$currentValues = array();
	return '';
}

// Check if there's a $_GET string present
if (strpos($_SERVER['REQUEST_URI'], '?') === false) {
	$queryAddString = '?';
} else {
	$queryAddString = '&';
}

// Build the link parameter array
if (in_array($value, $currentValues)) {
	// Show remove TPL
	$tpl = $removeTpl;
	$link = $_SERVER['REQUEST_URI'].$queryAddString.$addKey.'=remove';
} else {
	// Show add TPL
	$tpl = $addTpl;
	$link = $_SERVER['REQUEST_URI'].$queryAddString.$addKey.'=add';
}

return $uservaluelist->getChunk($tpl, array(
	'link' => $link,
	'value' => $value
));