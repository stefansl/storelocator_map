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
		$GLOBALS['TL_HEAD'][] = '<script src="http://maps.google.com/maps/api/js?language=de&amp;sensor=false"></script>';
		$GLOBALS['TL_HEAD'][] = '<script src="system/modules/storelocator_map/html/markerclusterer.js"></script>';

        $aCategories = array();
        $aCategories = deserialize($this->storelocator_list_categories);

		$objStores = $this->Database->prepare(" SELECT
		                                            id, name, street, email, url, phone, fax, street, postal, city, country, opening_times, longitude, latitude
		                                        FROM
		                                            `tl_storelocator_stores`
		                                        WHERE
		                                            pid IN(?)
		                                        ORDER BY id")
                                    ->execute(implode(',',$aCategories));

		$entries = array();

        $i=0;
        while ($objStores->next())
        {

            // Generate Link
            $link = null;


            if( $this->jumpTo ) {

                $objLink = $this->Database->prepare("SELECT * FROM tl_page WHERE id = ?;")->execute($this->jumpTo);
                $link = $this->generateFrontendUrl(
                    $objLink->fetchAssoc()
                    ,	( !$GLOBALS['TL_CONFIG']['useAutoItem'] ? 'store/' : '/' ).$objStores->id.'-'.standardize($objStores->name . ' ' . $objStores->city)
                );
            }

            $entries[$i] =  array(
                'id'        =>  $objStores->id,
                'name'      =>  $objStores->name,
                'street'    =>  $objStores->street,
                'email'     =>  $objStores->email,
                'url'       =>  $objStores->url,
                'phone'     =>  $objStores->phone,
                'fax'       =>  $objStores->fax,
                'postal'    =>  $objStores->postal,
                'city'      =>  $objStores->city,
                'country'   =>  $objStores->country,
                'opening_times'  =>  $objStores->opening_times,
                'longitude'  =>  $objStores->longitude,
                'latitude'  =>  $objStores->latitude,
                'jumpTo'    =>  $link
            );
            $i++;
        }



		$this->Template->entries	=	$entries;
        $json   =   json_encode($entries);

        // Temporär
        $this->Template->json   =   $json;
        
        $path   =   'system/modules/storelocator_map/html/' . $this->id . '-locations.json';
        $this->Template->path   =   $path;
        
        
        //JSON schreiben // TODO: Muss noch in tl_storelocator_map zum save_callback verschoben werden!!!
        $file   =   new File($path);
        $file->write($json);
        $this->log('Neue JSON-Datei erstellt', __CLASS__.'::'.__FUNCTION__, TL_CRON);

	}

    protected function getData() {
        $objStores = $this->Database->prepare(" SELECT * FROM `tl_storelocator_stores`")->execute();
        $entries = $objStores->fetchAllAssoc();

        $json   =   json_encode($entries);

    }

}
?>