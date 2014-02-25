<?php 

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
 
$GLOBALS['TL_DCA']['tl_module']['palettes']['storelocator_map'] = '{title_legend},name,headline,type;{config_legend:hide},storelocator_list_categories,jumpTo;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['storelocator_list_categories'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['storelocator_list_categories'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options_callback'        => array( 'tl_module_storelocator_map', 'getCategories' ),
    'eval'                    => array( 'mandatory'=>true, 'multiple'=>true ),
    'sql' 						=> "text NULL"
);

class tl_module_storelocator_map extends \Backend {


    public function getCategories() {
    
		$arrCalendars = array();
		$objCalendars = $this->Database->execute("SELECT id, title FROM tl_storelocator_category ORDER BY title");

		while ($objCalendars->next())
		{
				$arrCalendars[$objCalendars->id] = $objCalendars->title;
		}

		return $arrCalendars;
    }
	

}
 ?>
