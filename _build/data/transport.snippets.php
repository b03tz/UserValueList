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
/**
* @package uservaluelist
* @subpackage build
*/
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'addToUserValues',
    'description' => 'Saves and removes user values from the database.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/addToUserValues.snippet.php'),
));
$properties = include $sources['data'].'properties/properties.addtouservalues.php';
$snippets[1]->setProperties($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'getUserValues',
    'description' => 'Get\'s saved user values from the database.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getUserValues.snippet.php'),
));
$properties = include $sources['data'].'properties/properties.getuservalues.php';
$snippets[2]->setProperties($properties);


return $snippets;