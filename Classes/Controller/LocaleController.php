<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Aimeos locale controller.
 *
 * @package TYPO3
 */
class LocaleController extends AbstractController
{
	/**
	 * Processes requests and renders the locale selector.
	 */
	public function selectAction()
	{
		$client = \Aimeos\Client\Html\Locale\Select\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}
}
