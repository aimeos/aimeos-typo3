<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Custom;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * This class is basically taken from:
 * https://lbrmedia.net/ajaxexample/
 *
 * I would not recommend to use it like this, it is just here to demonstrate
 * that even with a crippled frontend bootstrap there will be no major performance gain...
 */
class EidRequestBootstrap
{
	/**
	 * @var ServerRequestInterface|NULL
	 */
	protected $request = null;

	/**
	 * @var ResponseInterface|NULL
	 */
	protected $response = null;

	/**
	 * @var array|NULL
	 */
	protected $params = null;

	/**
	 * @var TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|NULL
	 */
	protected $typoScriptFrontendController = null;

	/**
	 * @var array
	 */
	protected $pluginConfiguration;

	/**
	 * @var TYPO3\CMS\Extbase\Core\Bootstrap|NULL
	 */
	protected $bootstrap = null;

	/**
	 * @var array
	 */
	protected $bootstrapConfiguration;

	/**
	 * @var string|NULL
	 */
	protected $errorMessage = null;

	/**
	 * @var bool
	 */
	protected $isError = false;

	/**
	 * @var array
	 */
	protected $content = [];

	/**
	 * @var string
	 */
	protected $contentFormat = 'plain';

	/**
	 * Initializes the object
	 *
	 * @param ServerRequestInterface $request The request interface
	 * @param ResponseInterface $response The response interface
	 */
	public function __construct(  ServerRequestInterface $request, ResponseInterface $response  )
	{
		$this->request = $request;
		$this->response = $response;

		$this->checkRequest();
		$this->initFrontend();
		$this->initBootstrap();
	}

	/**
	 * Renders the AJAX call based on the $contentFormat variable and exits the request
	 *
	 * @return ResponseInterface|NULL
	 */
	public function render()
	{
		$this->processRequest();

		if ($this->isError) {
			return $this->renderAsError();
		}
		switch ($this->contentFormat) {
			case 'jsonhead':
			case 'jsonbody':
			case 'json':
				return $this->renderAsJSON();
				break;
			case 'javascript':
				return $this->renderAsJavascript();
				break;
			case 'xml':
				return $this->renderAsXML();
				break;
			default:
				return $this->renderAsPlain();
		}
	}

	/**
	 * Checks the request parameters
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function checkRequest()
	{
		// get parameters
		$this->params = [];

		$this->params['controller'] = isset( $this->request->getParsedBody()['controller'] ) ? $this->request->getParsedBody()['controller'] :
			( isset( $this->request->getQueryParams()['controller'] ) ? $this->request->getQueryParams()['controller'] : '' );

		$this->params['action'] = isset( $this->request->getParsedBody()['action'] ) ? $this->request->getParsedBody()['action'] :
			( isset( $this->request->getQueryParams()['action'] ) ? $this->request->getQueryParams()['action'] : '' );

		$this->params['plugin'] = isset( $this->request->getParsedBody()['plugin'] ) ? $this->request->getParsedBody()['plugin'] :
			( isset( $this->request->getQueryParams()['plugin'] ) ? $this->request->getQueryParams()['plugin'] : '' );

		$this->params['format'] = isset( $this->request->getParsedBody()['format'] ) ? $this->request->getParsedBody()['format'] :
			( isset( $this->request->getQueryParams()['format'] ) ? $this->request->getQueryParams()['format'] : '' );


		// check required parameters
		if ( empty( $this->params ) || empty( $this->params['controller'] ) || empty( $this->params['action'] ) ) {
			$this->setError( 'Missing required parameter' );
		}
	}

	/**
	 * Initializes the bootstrap
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function initBootstrap()
	{
		if ( $this->isError ) {
			return;
		}

		// create bootstrap
		$this->bootstrap = GeneralUtility::makeInstance( Bootstrap::class );

		// set configuration to call the plugin
		$this->bootstrapConfiguration = array(
			'pluginName' => !empty( $this->params['plugin'] ) ? $this->params['plugin'] : lcfirst( $this->params['controller'] ) . '-' . $this->params['action'],
			'vendorName' => 'Aimeos',
			'extensionName' => 'Aimeos',
			'controller' => $this->params['controller'],
			'action' => $this->params['action'],
			'mvc' => array(
				'requestHandlers' => array( 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' => 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' ),
			),
			'settings' => $this->pluginConfiguration['settings'],
			'persistence' => array( 'storagePid' => $this->pluginConfiguration['persistence']['storagePid'] ),
		);
	}

	/**
	 * Initializes the frontend
	 *
	 * @return void
	 */
	protected function initFrontend()
	{
		if ( $this->isError ) {
			return;
		}

		// init User
		$feUserObj = EidUtility::initFeUser();

		// set PID
		$pageId = GeneralUtility::_GP('id') ?: 1;

		// create and init frontend
		$this->typoScriptFrontendController = GeneralUtility::makeInstance(
			TypoScriptFrontendController::class,
			$GLOBALS['TYPO3_CONF_VARS'],
			$pageId,
			0,
			true
		);

		$GLOBALS['TSFE'] = $this->typoScriptFrontendController;

		$this->typoScriptFrontendController->connectToDB();
		$this->typoScriptFrontendController->fe_user = $feUserObj;
		$this->typoScriptFrontendController->id = $pageId;
		$this->typoScriptFrontendController->determineId();
		$this->typoScriptFrontendController->initTemplate();
		$this->typoScriptFrontendController->getConfigArray();

		EidUtility::initTCA();

		// get plugins TypoScript
		$typoScriptService = GeneralUtility::makeInstance( TypoScriptService::class );
		$this->pluginConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray( $this->typoScriptFrontendController->tmpl->setup['plugin.']['tx_aimeos.'] );
	}

	/**
	 * Checks the request parameters
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function processRequest()
	{
		if ( $this->isError ) {
			return;
		}

		// set content format
		$this->setContentFormat( $this->params['format'] );

		// run plugin and set content
		/*throw new \RuntimeException( $this->bootstrap->run('', $this->bootstrapConfiguration) );*/
		$this->setContent( $this->bootstrap->run('', $this->bootstrapConfiguration) );
	}

	/**
	 * Overwrites the existing content with the data supplied
	 *
	 * @param array $content The new content
	 * @return mixed The old content as array; if the new content was not an array, FALSE is returned
	 */
	protected function setContent( $content )
	{
		$oldcontent = false;
		if ( !empty( $content ) ) {
			$oldcontent = $this->content;
			$this->content = $content;
		}
		return $oldcontent;
	}

	/**
	 * Returns the content for the ajax call
	 *
	 * @return mixed The content for a specific key or the whole content
	 */
	protected function getContent( $key = '' )
	{
		return $key && array_key_exists( $key, $this->content ) ? $this->content[$key] : $this->content;
	}

	/**
	 * Sets the content format for the ajax call
	 *
	 * @param string $format Can be one of 'plain' (default), 'xml', 'json', 'javascript', 'jsonbody' or 'jsonhead'
	 * @return void
	 */
	protected function setContentFormat($format)
	{
		if ( ArrayUtility::inArray(['plain', 'xml', 'json', 'jsonhead', 'jsonbody', 'javascript'], $format ) ) {
			$this->contentFormat = $format;
		}
	}

	/**
	 * Sets an error message and the error flag
	 *
	 * @param string $errorMsg The error message
	 * @return void
	 */
	protected function setError( $errorMsg = '' )
	{
		$this->errorMessage = $errorMsg;
		$this->isError = true;
	}

	/**
	 * Checks whether an error occurred during the execution or not
	 *
	 * @return bool Whether this AJAX call had errors
	 */
	protected function isError()
	{
		return $this->isError;
	}

	/**
	 * Renders the AJAX call in XML error style to handle with JS
	 * the "responseXML" of the transport object will be filled with the error message then
	 *
	 * @return ResponseInterface
	 */
	protected function renderAsError()
	{
		$this->response = $this->response
			->withStatus( 500, ' (AJAX)' )
			->withHeader( 'Content-type', 'text/xml; charset=utf-8' )
			->withHeader( 'X-JSON', 'false' );

		$this->response->getBody()->write( '<t3err>' . htmlspecialchars($this->errorMessage) . '</t3err>' );
		return $this->response;
	}

	/**
	 * Renders the AJAX call with text/html headers
	 * the content will be available in the "responseText" value of the transport object
	 *
	 * @return ResponseInterface
	 * @throws \InvalidArgumentException
	 */
	protected function renderAsPlain()
	{
		$this->response = $this->response
			->withHeader( 'Content-type', 'text/plain; charset=utf-8' )
			->withHeader( 'X-JSON', 'true' );

		$this->response->getBody()->write( $this->content );
		return $this->response;
	}

	/**
	 * Renders the AJAX call with text/xml headers
	 * the content will be available in the "responseXML" value of the transport object
	 *
	 * @return ResponseInterface
	 * @throws \InvalidArgumentException
	 */
	protected function renderAsXML()
	{
		$this->response = $this->response
			->withHeader( 'Content-type', 'text/xml; charset=utf-8' )
			->withHeader( 'X-JSON', 'true' );

		$this->response->getBody()->write( implode( '', $this->content ) );
		return $this->response;
	}

	/**
	 * Renders the AJAX call with JSON evaluated headers
	 * note that you need to have requestHeaders: {Accept: 'application/json'},
	 * in your AJAX options of your AJAX request object in JS
	 *
	 * the content will be available
	 * - in the second parameter of the onSuccess / onComplete callback
	 * - and in the xhr.responseText as a string (except when contentFormat = 'jsonhead')
	 * you can evaluate this in JS with xhr.responseText.evalJSON();
	 *
	 * @return ResponseInterface
	 * @throws \InvalidArgumentException
	 */
	protected function renderAsJSON()
	{
		$this->response = $this->response->withHeader( 'Content-type', 'application/json; charset=utf-8' );

		$content = json_encode( $this->content );
		// Bring content in xhr.responseText except when in "json head only" mode
		if ( $this->contentFormat === 'jsonhead' ) {
			$this->response = $this->response->withHeader( 'X-JSON', $content );
		} else {
			$this->response = $this->response->withHeader( 'X-JSON', 'true' );
			$this->response->getBody()->write( $content );
		}
		return $this->response;
	}

	/**
	 * Renders the AJAX call as inline JSON inside a script tag. This is useful
	 * when an iframe is used as the AJAX transport.
	 *
	 * @return ResponseInterface
	 * @throws \InvalidArgumentException
	 */
	protected function renderAsJavascript()
	{
		$this->response = $this->response->withHeader( 'Content-type', 'application/javascript; charset=utf-8' );
		$this->response->getBody()->write( $this->content );
		return $this->response;
	}

}
