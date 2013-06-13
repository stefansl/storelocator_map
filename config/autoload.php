<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Storelocator_map
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'ModuleStoreLocatorMap' => 'system/modules/storelocator_map/ModuleStoreLocatorMap.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_storelocator_map' => 'system/modules/storelocator_map/templates',
));
