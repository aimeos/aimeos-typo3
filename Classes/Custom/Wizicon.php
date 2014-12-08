<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\AimeosShop\Custom;


/**
 * Class that adds the wizard icon.
 *
 * @package TYPO3_Aimeos
 */
class Wizicon
{
	/**
	 * Adds the wizard icon
	 *
	 * @param array Input array with wizard items for plugins
	 * @return array Modified input array, having the item for Aimeos added.
	 */
	public function proc( $wizardItems )
	{
		$path = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos_shop' );
		$file = $path . 'Resources/Private/Language/Extension.xml';
		$xml = \TYPO3\CMS\Core\Utility\GeneralUtility::readLLfile( $file, $GLOBALS['LANG']->lang );

		$wizardItems['plugins_tx_aimeos'] = array(
			'icon' => $path . 'Resources/Public/images/aimeos-wizicon.png',
			'title' => $GLOBALS['LANG']->getLLL( 'ext-wizard-title', $xml ),
			'description' => $GLOBALS['LANG']->getLLL( 'ext-wizard-description', $xml ),
			'params' => '&defVals[tt_content][CType]=list'
		);

		return $wizardItems;
	}
}


if( defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aimeos/Classes/Custom/Wizicon.php'] ) {
	include_once( $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aimeos/Classes/Custom/Wizicon.php'] );
}
