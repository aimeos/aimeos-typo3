<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2021
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Middleware;


/**
 * Aimeos middleware helper.
 *
 * @package TYPO3
 */
class PageRoutingHandler implements \Psr\Http\Server\MiddlewareInterface
{
	/**
	 * @var \TYPO3\CMS\Core\Routing\PageArguments|null
	 */
	private static $routingPageArguments = null;

	/**
	 * Retrieve the page routing attributes from the request as early as possible.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Server\RequestHandlerInterface $handler
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function process( \Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler ) : \Psr\Http\Message\ResponseInterface
	{
		if( empty( $request->getAttributes()['routing'] ) === false )
		{
			static::$routingPageArguments = clone $request->getAttributes()['routing'];
		}

		// Let the others do their thing.
		return $handler->handle( $request );
	}

	/**
	 * Static getter for the page routing arguments.
	 *
	 * @return \TYPO3\CMS\Core\Routing\PageArguments|null
	 */
	public static function getRoutingPageArguments() : ?\TYPO3\CMS\Core\Routing\PageArguments
	{
		return static::$routingPageArguments;
	}
}
