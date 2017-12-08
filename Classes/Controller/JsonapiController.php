<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use Zend\Diactoros\Response;


/**
 * Controller for the frontend JSON REST API
 *
 * @package TYPO3
 */
class JsonapiController extends AbstractController
{
	/**
	 * Dispatches the REST API requests
	 *
	 * @return string Generated output
	 */
	public function indexAction()
	{
		$resource = $related = null;

		if( $this->request->hasArgument( 'resource' )
			&& ( $value = $this->request->getArgument( 'resource' ) ) != ''
		) {
			$resource = $value;
		}

		if( $this->request->hasArgument( 'related' )
			&& ( $value = $this->request->getArgument( 'related' ) ) != ''
		) {
			$related = $value;
		}

		switch( $this->request->getMethod() )
		{
			case 'DELETE': return $this->deleteAction( $resource, $related );
			case 'PATCH': return $this->patchAction( $resource, $related );
			case 'POST': return $this->postAction( $resource, $related );
			case 'PUT': return $this->putAction( $resource, $related );
			case 'GET': return $this->getAction( $resource, $related );
			default: return $this->optionsAction( $resource );
		}
	}


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "basket"
	 * @param string Related resource, e.g. "product"
	 * @return string Generated output
	 */
	public function deleteAction( $resource, $related )
	{
		$response = $this->createClient( $resource, $related )->delete( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param string Resource location, e.g. "basket"
	 * @param string Related resource, e.g. "product"
	 * @return string Generated output
	 */
	public function getAction( $resource, $related )
	{
		$response = $this->createClient( $resource, $related )->get( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "basket"
	 * @param string Related resource, e.g. "product"
	 * @return string Generated output
	 */
	public function patchAction( $resource, $related )
	{
		$response = $this->createClient( $resource, $related )->patch( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "basket"
	 * @param string Related resource, e.g. "product"
	 * @return string Generated output
	 */
	public function postAction( $resource, $related )
	{
		$response = $this->createClient( $resource, $related )->post( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param string Resource location, e.g. "basket"
	 * @param string Related resource, e.g. "product"
	 * @return string Generated output
	 */
	public function putAction( $resource, $related )
	{
		$response = $this->createClient( $resource, $related )->put( $this->getPsrRequest(), new Response() );
		return $this->setPsrResponse( $response );
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param string Resource location, e.g. "product"
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
	 * @param string Resource location, e.g. "basket"
	 * @param string|null Related resource, e.g. "product"
	 * @return \Aimeos\Client\JsonApi\Iface Jsonapi client
	 */
	protected function createClient( $resource, $related = null )
	{
		$context = $this->getContext( 'client/jsonapi/templates' );
		return \Aimeos\Client\JsonApi\Factory::createClient( $context, $resource . '/' . $related );
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
