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
     * The request handed over
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

	/**
     * The response handed over
	 * @var Psr\Http\Message\ResponseInterface
	 */
	protected $response;

	/**
	 * @var array
	 */
	protected $pageId;

	/**
	 * @var array
	 */
	protected $parameters;

    /**
     * Instance of the TSFE object
     * @var TypoScriptFrontendController
     */
    protected $controller;

	/**
	 * @var array
	 */
	protected $pluginConfiguration;

	/**
	 * @var TYPO3\CMS\Extbase\Core\Bootstrap
	 */
	protected $bootstrap;

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
		$this->initializeFrontend();
		$this->initializeBootstrap();
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
     * Returns the request value of incoming data from POST or GET, with priority to POST (that is equalent to 'GP' order)
     *
     * @param string $var GET/POST var to return
     * @return mixed POST var named $var and if not set, the GET var of the same name.
     */
    public function _GP( $var )
    {
        if (empty($var)) {
            return;
        }
        if (!empty($this->request->getParsedBody()[$var])) {
            $value = $this->request->getParsedBody()[$var];
        } elseif (!empty($this->request->getQueryParams()[$var])) {
            $value = $this->request->getQueryParams()[$var];
        } else {
            $value = null;
        }
        // This is there for backwards-compatibility, in order to avoid NULL
        if (isset($value) && !is_array($value)) {
            $value = (string)$value;
        }
        return $value;
    }

    /**
     * Initializes output compression when enabled, could be split up and put into Bootstrap
     * at a later point
     */
    protected function initializeOutputCompression()
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['FE']['compressionLevel'] && extension_loaded('zlib')) {
            if (MathUtility::canBeInterpretedAsInteger($GLOBALS['TYPO3_CONF_VARS']['FE']['compressionLevel'])) {
                @ini_set('zlib.output_compression_level', $GLOBALS['TYPO3_CONF_VARS']['FE']['compressionLevel']);
            }
            ob_start([GeneralUtility::makeInstance(CompressionUtility::class), 'compressionOutputHandler']);
        }
    }

    /**
     * Creates an instance of TSFE and sets it as a global variable
     *
     * @return void
     */
    protected function initializeController()
    {
        $this->controller = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            $this->pageId,
            0,
            true
        );
        // setting the global variable for the controller
        // We have to define this as reference here, because there is code around
        // which exchanges the TSFE object in the global variable. The reference ensures
        // that the $controller member always works on the same object as the global variable.
        // This is a dirty workaround and bypasses the protected access modifier of the controller member.
        $GLOBALS['TSFE'] = &$this->controller;
    }

	/**
	 * Checks the request parameters
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function checkRequest()
	{
		$this->pageId = $this->_GP('tID') ? $this->_GP('tID') : 1;

		// get parameters
		$this->parameters = [];

		$this->parameters['controller'] = $this->_GP('controller');
		$this->parameters['action'] = $this->_GP('action');
		$this->parameters['plugin'] = $this->_GP('plugin');
		$this->parameters['format'] = $this->_GP('format');

		// check required parameters
		if ( empty( $this->parameters['controller'] ) || empty( $this->parameters['action'] ) ) {
			$this->setError( 'Missing required parameter' );
		}
	}

	/**
	 * Initializes the bootstrap
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	protected function initializeBootstrap()
	{
		if ( $this->isError ) {
			return;
		}

		// create bootstrap
		$this->bootstrap = GeneralUtility::makeInstance( Bootstrap::class );

		// set configuration to call the plugin
		$this->bootstrapConfiguration = array(
			'pluginName' => !empty( $this->parameters['plugin'] ) ? $this->parameters['plugin'] : lcfirst( $this->parameters['controller'] ) . '-' . $this->parameters['action'],
			'vendorName' => 'Aimeos',
			'extensionName' => 'Aimeos',
			'controller' => $this->parameters['controller'],
			'action' => $this->parameters['action'],
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
	protected function initializeFrontend()
	{
		if ( $this->isError ) {
			return;
		}

		$feUserObj = EidUtility::initFeUser();

		// copied from TYPO3\CMS\Frontend\Http\RequestHandler
        $this->initializeController();

        if ($GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_force']
            && !GeneralUtility::cmpIP(
                GeneralUtility::getIndpEnv('REMOTE_ADDR'),
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])
        ) {
            $this->controller->pageUnavailableAndExit('This page is temporarily unavailable.');
        }

        $this->controller->connectToDB();
        $this->controller->sendRedirect();

        // Output compression
        $this->initializeOutputCompression();

        // Initializing the Frontend User
        //$this->controller->initFEuser();
        $this->controller->fe_user = $feUserObj;

		//EidUtility::initTCA();

		/*throw new \RuntimeException( $this->pageId );*/
        $this->controller->clear_preview();
        $this->controller->id = $this->pageId;
        $this->controller->determineId();
		/*throw new \RuntimeException( $this->controller->id );*/

        // Starts the template
        $this->controller->initTemplate();

        // Get from cache
        $this->controller->getFromCache();

        // Get config if not already gotten
        // After this, we should have a valid config-array ready
        $this->controller->getConfigArray();

        // Setting language and locale
        $this->controller->settingLanguage();
        $this->controller->settingLocale();

		//$this->controller->getCompressedTCarray(); //Comment this line when used for TYPO3 7.6.0 on wards
		//$this->controller->includeTCA(); //Comment this line when used for TYPO3 7.6.0 on wards

		// Get plugins TypoScript
		$typoScriptService = GeneralUtility::makeInstance( TypoScriptService::class );
		$this->pluginConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray( $this->controller->tmpl->setup['plugin.']['tx_aimeos.'] );
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
		$this->setContentFormat( $this->parameters['format'] );

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
