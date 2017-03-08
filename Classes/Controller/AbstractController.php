<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Abstract class with common functionality for all controllers.
 *
 * @package TYPO3
 */
abstract class AbstractController
	extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	private static $context;
	private $contextBE;


	/**
	 * Creates a new configuration object.
	 *
	 * @return \Aimeos\MW\Config\Iface Configuration object
	 * @deprecated Use \Aimeos\Aimeos\Base::getConfig() directly
	 */
	protected function getConfig()
	{
		return Base::getConfig( (array) $this->settings );
	}


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

			if( $this->request->hasArgument( 'lang' )
				&& ( $value = $this->request->getArgument( 'lang' ) ) != ''
			) {
				$lang = $value;
			}

			if( $this->request->hasArgument( 'site' )
				&& ( $value = $this->request->getArgument( 'site' ) ) != ''
			) {
				$site = $value;
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
