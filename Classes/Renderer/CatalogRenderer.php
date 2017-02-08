<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017-
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Renderer;


use Aimeos\Aimeos\Base;
use Helhum\TyposcriptRendering\Mvc\Request;
use Helhum\TyposcriptRendering\Mvc\Response;
use Helhum\TyposcriptRendering\Renderer\RenderingContext;
use Helhum\TyposcriptRendering\Renderer\RenderingInterface;


/**
 * Aimeos catalog renderer.
 *
 * @package TYPO3
 */
class CatalogRenderer extends AbstractRenderer
{

	/**
	 * Whether the required arguments for rendering are present or not
	 *
	 * @param Request $request
	 *
	 * @return bool
	 */
	public function canRender(Request $request)
	{
		return $request->hasArgument('extension') && $request->hasArgument('plugin') &&
			$request->getArgument('extension') === 'aimeos' && compare( $request->getArgument('plugin'), 'catalog-', 1) == 0;
	}

	/**
	 * Evaluates request arguments, renders a string based on them
	 * and sets the string content to the response.
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param RenderingContext $renderingContext
	 *
	 * @return void
	 */
	public function renderRequest(Request $request, Response $response, RenderingContext $renderingContext)
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Aimeos\Client\Html\Catalog\Suggest\Factory::createClient( $this->getContext(), $templatePaths );

		$response->setContent( $this->getClientOutput( $client ) );
	}
}
