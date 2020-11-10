<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


/**
 * Controller for the admin interface
 *
 * @package TYPO3
 */
class AdminController extends AbstractController
{
	/**
	 * Forwards to the configured admin interface
	 */
	public function indexAction()
	{
		$site = 'default';

		if( isset( $GLOBALS['BE_USER']->user['siteid'] ) && $GLOBALS['BE_USER']->user['siteid'] != '' )
		{
			$siteManager = \Aimeos\MShop::create( $this->getContextBackend(), 'locale/site' );
			$siteId = current( array_reverse( explode( '.', trim( $GLOBALS['BE_USER']->user['siteid'], '.' ) ) ) );
			$site = ( $siteId ? $siteManager->get( $siteId )->getCode() : 'default' );
		}

		$this->forward( 'search', 'Jqadm', null, ['site' => $site] );
	}
}
