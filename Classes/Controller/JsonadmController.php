<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use \Aimeos\Aimeos\Base;


/**
 * Controller for the JSON API
 *
 * @package TYPO3
 */
class JsonadmController extends AbstractController
{
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
		$resource = null;

		if( $this->request->hasArgument( 'resource' ) ) {
			$resource = $this->request->getArgument( 'resource' );
		}

		switch( $this->request->getMethod() )
		{
			case 'DELETE': return $this->deleteAction( $resource );
			case 'PATCH': return $this->patchAction( $resource );
			case 'POST': return $this->postAction( $resource );
			case 'PUT': return $this->putAction( $resource );
			case 'GET': return $this->getAction( $resource );
			default: return $this->optionsAction( $resource );
		}
	}


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function deleteAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;

		$client = $this->createClient( $resource );
		$result = $client->delete( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function getAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;

		$client = $this->createClient( $resource );
		$result = $client->get( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function patchAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;

		$client = $this->createClient( $resource );
		$result = $client->patch( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function postAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;

		$client = $this->createClient( $resource );
		$result = $client->post( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function putAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;

		$client = $this->createClient( $resource );
		$result = $client->put( $content, $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return string Generated output
	 */
	public function optionsAction( $resource )
	{
		$content = file_get_contents( 'php://input' );
		$header = array();
		$status = 500;
		$lang = null;

		$client = $this->createClient( $resource );
		$result = $client->options( $content, $header, $status );

		if( ( $json = json_decode( $result, true ) ) !== null )
		{
			$json['meta']['prefix'] = 'tx_aimeos_web_aimeostxaimeosadmin';
			$result = json_encode( $json );
		}

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the resource client
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @return \Aimeos\Admin\JsonAdm\Iface Jsonadm client
	 */
	protected function createClient( $resource )
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'admin/jsonadm/templates' );
		$context = $this->getContextBackend( $templatePaths );

		return \Aimeos\Admin\JsonAdm\Factory::createClient( $context, $templatePaths, $resource );
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
