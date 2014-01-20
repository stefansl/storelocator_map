<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  CLICKPRESS Internetagentur <www.clickpress.de>
 * @author     Stefan Schulz-Lauterbach <ssl@clickpress.de>
 * @package    storelocator_map
 * @license    LGPL
 * @filesource
 */


class ModuleStoreLocatorMap extends ModuleStoreLocatorList {


	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_storelocator_map';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {

		if( TL_MODE == 'BE' ) {

			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### STORELOCATOR MAP ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		return parent::generate();
	}
	
	
	/**
	 * Generate module
	 */
	protected function compile() {

		$this->Template = new FrontendTemplate($this->strTemplate);
		$GLOBALS['TL_HEAD'][] = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
		$GLOBALS['TL_HEAD'][] = '<script src="system/modules/storelocator_map/html/markerclusterer.js"></script>';
		$objStores = $this->Database->prepare(" SELECT * FROM `tl_storelocator_stores`")->execute();

		$entries = array();
		$entries = $objStores->fetchAllAssoc();

		$this->Template->entries	=	$entries;

        $json   =   json_encode($entries);

        // Temporär
        $this->Template->json   =   $json;

        $file   =   new File('system/modules/storelocator_map/html/locations.json');
        $file->write($json);


	}

    protected function getData() {
        $objStores = $this->Database->prepare(" SELECT * FROM `tl_storelocator_stores`")->execute();
        $entries = $objStores->fetchAllAssoc();

        $json   =   json_encode($entries);

        $this->log($json, __CLASS__.'::'.__FUNCTION__, TL_GENERAL);

    }

}
?>