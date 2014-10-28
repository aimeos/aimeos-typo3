<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


/**
 * Aimeos locale controller.
 *
 * @package TYPO3_Aimeos
 */
class Tx_Aimeos_Controller_LocaleController extends Tx_Aimeos_Controller_Abstract
{
	/**
	 * Processes requests and renders the locale selector.
	 */
	public function selectAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Locale_Select_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}
}
