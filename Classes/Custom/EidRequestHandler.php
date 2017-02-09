<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Custom;


use Aimeos\Aimeos\Custom\EidRequestBootstrap;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * Aimeos eID request handler
 */
class EidRequestHandler
{
	protected $bootstrap = null;

	/**
	 * Routes the given eID action to the related ExtDirect method with the necessary
	 * ajax object.
	 *
	 * @param string $ajaxID
	 * @return void
	 */
	protected function routeAction($ajaxID)
	{
		EidUtility::initLanguage();
		$ajaxScript = $GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['ExtDirect::' . $ajaxID]['callbackMethod'];
		$this->ajaxObject = GeneralUtility::makeInstance(AjaxRequestHandler::class, 'ExtDirect::' . $ajaxID);
		$parameters = [];
		GeneralUtility::callUserFunction($ajaxScript, $parameters, $this->ajaxObject, false, true);
	}

	/**
	 * Renders/Echoes the ajax output
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface|NULL
	 * @throws \InvalidArgumentException
	 */
	public function processRequest( ServerRequestInterface $request, ResponseInterface $response )
	{
		$bootstrap = GeneralUtility::makeInstance( EidRequestBootstrap::class, $request, $response );
		return $bootstrap->render();
	}
}
