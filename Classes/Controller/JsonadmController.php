<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use Zend\Diactoros\Response;


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
		parent::initializeAction();
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

		if( $this->request->hasArgument( 'resource' )
			&& ( $value = $this->request->getArgument( 'resource' ) ) != ''
		) {
			$resource = $value;
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
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function deleteAction( $resource )
	{
		$response = $this->createClient( $resource )->delete( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function getAction( $resource )
	{
		$response = $this->createClient( $resource )->get( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function patchAction( $resource )
	{
		$response = $this->createClient( $resource )->patch( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function postAction( $resource )
	{
		$response = $this->createClient( $resource )->post( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function putAction( $resource )
	{
		$response = $this->createClient( $resource )->put( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return string Generated output
	 */
	public function optionsAction( $resource )
	{
		$response = $this->createClient( $resource )->options( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Returns the resource client
	 *
	 * @param string Resource location, e.g. "product/property/type"
	 * @return \Aimeos\Admin\JsonAdm\Iface Jsonadm client
	 */
	protected function createClient( $resource )
	{
		$context = $this->getContextBackend( 'admin/jsonadm/templates' );
		return \Aimeos\Admin\JsonAdm\Factory::createClient( $context, Base::getAimeos(), $resource );
	}


	/**
	 * Returns a PSR-7 request object for the current request
	 *
	 * @return \Psr\Http\Message\RequestInterface PSR-7 request object
	 */
	protected function getPsrRequest()
	{
		return \Zend\Diactoros\ServerRequestFactory::fromGlobals();
	}


	/**
	 * Set the response data from a PSR-7 response object and returns the message content
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response PSR-7 response object
	 * @return string Generated output
	 */
	protected function setPsrResponse( \Psr\Http\Message\ResponseInterface $response )
	{
		$this->response->setStatus( $response->getStatusCode() );

		foreach( $response->getHeaders() as $key => $value ) {
			foreach( (array) $value as $val ) {
				$this->response->setHeader( $key, $val );
			}
		}

		return (string) $response->getBody();
	}
}
