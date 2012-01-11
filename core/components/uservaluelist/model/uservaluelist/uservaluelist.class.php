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
 * This file is the main class file for userValueList.
 *
 * @copyright Copyright (C) 2011, SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @author SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @package uservaluelist
 */
class userValueList {
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The request object for the current state
     * @var userValueListControllerRequest $request
     */
    public $request;
    /**
     * The controller for the current request
     * @var userValueListController $controller
     */
    public $controller = null;

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        /* allows you to set paths in different environments
         * this allows for easier SVN management of files
         */
        $corePath = $this->modx->getOption('uservaluelist.core_path',null,$modx->getOption('core_path').'components/uservaluelist/');
        $assetsPath = $this->modx->getOption('uservaluelist.assets_path',null,$modx->getOption('assets_path').'components/uservaluelist/');
        $assetsUrl = $this->modx->getOption('uservaluelist.assets_url',null,$modx->getOption('assets_url').'components/uservaluelist/');

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',

            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php'
        ),$config);

        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('uservaluelist:default');
        }
    }

    /**
     * Initializes userValueList based on a specific context.
     *
     * @access public
     * @param string $ctx The context to initialize in.
     * @return string The processed content.
     */
    public function initialize($ctx = 'mgr') {
        $output = '';

        return $output;
    }
	
	public function testFunction() {
		return 'test';
	}
	
    /**
    * Gets a Chunk and caches it; also falls back to file-based templates
    * for easier debugging.
    *
    * @author Shaun McCormick
    * @access public
    * @param string $name The name of the Chunk
    * @param array $properties The properties for the Chunk
    * @return string The processed content of the Chunk
    */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
    * Returns a modChunk object from a template file.
    *
    * @author Shaun McCormick
    * @access private
    * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
    * @param string $postFix The postfix to append to the name
    * @return modChunk/boolean Returns the modChunk object if found, otherwise
    * false.
    */
    private function _getTplChunk($name,$postFix = '.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].$name.$postFix;

        if (file_exists($f)) {
            $o = file_get_contents($f);
            /* @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }
	
	public function isLoggedIn() {
		return $this->modx->user->hasSessionContext($this->modx->context->get('key'));
	} 
	
	public function getUserListValue($key) {
		$userProfile = $this->modx->user->getOne('Profile');
		
		if ($userProfile != null) {
			$extendedFields = $userProfile->get('extended');
			
			if (isset($extendedFields[$key])) {
				return json_decode($extendedFields[$key], true);
			}
		}
		
		return array();
	}
	
	public function saveUserList($key, $list) {
		$userProfile = $this->modx->user->getOne('Profile');
		
		if ($userProfile != null) {
			$extendedFields = $userProfile->get('extended');
			
			$extendedFields[$key] = json_encode($list);
					
			$userProfile->set('extended', $extendedFields);
			$userProfile->save();
		}
	}
	
	public function checkListValue($key, $addKey, $value) {
		if (isset($_REQUEST[$addKey])) {
			$currentValues = $this->getUserListValue($key);
			
			if ($_REQUEST[$addKey] == 'add') {
				if (!in_array($value, $currentValues)) {
					// Value was not in array, add it and redirect to current page
					$currentValues[] = $value;
					
					// Save the userlist and redirect to current page
					$this->saveUserList($key, $currentValues);
					$this->modx->sendRedirect($this->getRedirectLink($addKey));
				}
			}
			
			if ($_REQUEST[$addKey] == 'remove') {
				if (in_array($value, $currentValues)) {
					// Value is in array, remove it and redirect to current page
					$loopValues = $currentValues;
					foreach($loopValues as $currentKey => $currentValue) {
						if ($currentValue == $value) {
							unset($currentValues[$currentKey]);
						}	
					} 
					
					// Save the userlist and redirect to current page
					$this->saveUserList($key, $currentValues);
					$this->modx->sendRedirect($this->getRedirectLink($addKey));
				}
			}

		}
	}
	
	private function getRedirectLink($key) {
		$replaceArray = array(
			$key.'=add',
			$key.'=remove'
		);
		
		$url = str_replace($replaceArray, '', $_SERVER['REQUEST_URI']);
		$url = str_replace('&&', '&', $url);
		return str_replace('?&', '?', $url);
	}
}