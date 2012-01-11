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

$key = $modx->getOption('key', $scriptProperties, 'userValueList');
$separator = $modx->getOption('separator', $scriptProperties, ',');

// Get the extra fields
$currentValues = $uservaluelist->getUserListValue($key);

if (is_array($currentValues)) {
	return implode($separator, $currentValues);
}

return '';