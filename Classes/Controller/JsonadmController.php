<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Controller;


use \Aimeos\Aimeos\Base;


/**
 * Controller for the JSON API
 *
 * @package TYPO3_Aimeos
 */
class JsonadmController extends AbstractController
{
	private $context;


	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$this->uriBuilder->setArgumentPrefix( 'tx_aimeos_web_aimeostxaimeosadmin' );
	}


	/**
	 * Dispatches the REST API requests
	 *
	 * @return string Generated output
	 */
	public function indexAction()
	{
		$resource = $site = $id = null;

		if( $this->request->hasArgument( 'resource' ) ) {
			$resource = $this->request->getArgument( 'resource' );
		}

		if( $this->request->hasArgument( 'site' ) ) {
			$site = $this->request->getArgument( 'site' );
		}

		if( $this->request->hasArgument( 'id' ) ) {
			$id = $this->request->getArgument( 'id' );
		}

		switch( $this->request->getMethod() )
		{
			case 'DELETE': return $this->deleteAction( $resource, $site, $id );
			case 'PATCH': return $this->patchAction( $resource, $site, $id );
			case 'POST': return $this->postAction( $resource, $site, $id );
			case 'PUT': return $this->putAction( $resource, $site, $id );
			case 'GET': return $this->getAction( $resource, $site, $id );
			default: return $this->optionsAction( $resource, $site );
		}
	}


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function deleteAction( $resource, $site = 'default', $id = '' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->delete( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function getAction( $resource, $site = 'default', $id = '' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->get( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function patchAction( $resource, $site = 'default', $id = '' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->patch( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique ID of the resource
	 * @return string Generated output
	 */
	public function postAction( $resource, $site = 'default', $id = '' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->post( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function putAction( $resource, $site = 'default', $id = '' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->put( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @return string Generated output
	 */
	public function optionsAction( $resource = '', $site = 'default' )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		if( $this->request->hasArgument( 'lang' ) ) {
			$lang = $this->request->getArgument( 'lang' );
		}

		$cntl = $this->createController( $site, $resource, $lang );
		$result = $cntl->options( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the resource controller
	 *
	 * @param string $sitecode Unique site code
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $lang Language code
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function createController( $sitecode, $resource, $lang )
	{
		$lang = ( $lang ? $lang : 'en' );
		$context = $this->getContext( $sitecode, $lang );
		$templatePaths = Base::getAimeos()->getCustomPaths( 'controller/jsonadm/templates' );

		$view = Base::getView( $context->getConfig(), $this->uriBuilder, $templatePaths, $this->request, $lang );
		$context->setView( $view );

		return \Aimeos\Controller\JsonAdm\Factory::createController( $context, $templatePaths, $resource );
	}


	/**
	 * Sets the locale item in the given context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @param string $lang ISO language code, e.g. "en" or "en_GB"
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected function getContext( $sitecode, $lang )
	{
		if( !isset( $this->context ) )
		{
			$config = $this->getConfig( $this->settings );
			$context = Base::getContext( $config );

			$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

			try
			{
				$localeItem = $localeManager->bootstrap( $sitecode, '', '', false );
				$localeItem->setLanguageId( null );
				$localeItem->setCurrencyId( null );
			}
			catch( \Aimeos\MShop\Locale\Exception $e )
			{
				$localeItem = $localeManager->createItem();
			}

			$context->setLocale( $localeItem );
			$context->setI18n( Base::getI18n( array( $lang ) ) );

			$context->setEditor( $GLOBALS['BE_USER']->user['username'] );

			$this->context = $context;
		}

		return $this->context;
	}


	/**
	 * Creates a new response object
	 *
	 * @param integer $status HTTP status
	 * @param array $header List of HTTP headers
	 * @return \TYPO3\Flow\Http\Response HTTP response object
	 */
	protected function setResponse( $status, array $header )
	{
		$this->response->setStatus( $status );

		foreach( $header as $key => $value ) {
			$this->response->setHeader( $key, $value );
		}
	}
}
