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
		$this->forward( 'search', 'Jqadm' );
	}
}
