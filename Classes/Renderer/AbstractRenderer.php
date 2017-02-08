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
abstract class AbstractRenderer implements RenderingInterface
{
	private static $context;
	private $contextBE;

	public $settingsOrg;


	/**
	 * Returns the context item for the frontend
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContext()
	{
		$config = Base::getConfig( (array) $this->settings );

		if( !isset( self::$context ) )
		{
			$context = Base::getContext( $config );
			$locale = Base::getLocale( $context, $this->request );
			$context->setI18n( Base::getI18n( array( $locale->getLanguageId() ), $config->get( 'i18n', array() ) ) );
			$context->setLocale( $locale );

			self::$context = $context;
		}

		// Use plugin specific configuration
		self::$context->setConfig( $config );

		$langid = self::$context->getLocale()->getLanguageId();
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html/templates' );
		self::$context->setView( Base::getView( $config, $this->uriBuilder, $templatePaths, $this->request, $langid ) );

		return self::$context;
	}


	/**
	 * Returns the context item for backend operations
	 *
	 * @param array $templatePaths List of paths to the view templates
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContextBackend( array $templatePaths = array(), $withView = true )
	{
		if( !isset( $this->contextBE ) )
		{
			$lang = 'en';
			$site = 'default';

			if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
				$lang = $GLOBALS['BE_USER']->uc['lang'];
			}

			if( $this->request->hasArgument( 'lang' ) ) {
				$lang = $this->request->getArgument( 'lang' );
			}

			if( $this->request->hasArgument( 'site' ) ) {
				$site = $this->request->getArgument( 'site' );
			}

			$config = Base::getConfig( (array) $this->settings );
			$context = Base::getContext( $config );

			$locale = Base::getLocaleBackend( $context, $site );
			$context->setLocale( $locale );

			$i18n = Base::getI18n( array( $lang, 'en' ), $config->get( 'i18n', array() ) );
			$context->setI18n( $i18n );

			if( $withView )
			{
				$view = Base::getView( $config, $this->uriBuilder, $templatePaths, $this->request, $lang, false );
				$context->setView( $view );
			}

			$this->contextBE = $context;
		}

		return $this->contextBE;
	}


	/**
	 * Returns the locale object for the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 * @deprecated Use \Aimeos\Aimeos\Base::getLocale() directly
	 */
	protected function getLocale( \Aimeos\MShop\Context\Item\Iface $context )
	{
		return Base::getLocale( $context, $this->request );
	}


	/**
	 * Returns the output of the client and adds the header.
	 *
	 * @param Client_Html_Interface $client Html client object
	 * @return string HTML code for inserting into the HTML body
	 */
	protected function getClientOutput( \Aimeos\Client\Html\Iface $client )
	{
		$client->setView( $this->getContext()->getView() );
		$client->process();

		$this->response->addAdditionalHeaderData( (string) $client->getHeader() );

		return $client->getBody();
	}


	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$this->uriBuilder->setArgumentPrefix( 'ai' );
	}


	/**
	 * Disables Fluid views for performance reasons
	 *
	 * return null
	 */
	protected function resolveView()
	{
		return null;
	}

}
