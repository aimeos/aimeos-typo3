<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2016
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
	private $context;
	private static $locale;


	/**
	 * Creates a new configuration object.
	 *
	 * @return MW_Config_Interface Configuration object
	 */
	protected function getConfig()
	{
		$settings = (array) $this->settings;

		if( isset( $this->settings['typo3']['tsconfig'] ) )
		{
			$tsconfig = Base::parseTS( $this->settings['typo3']['tsconfig'] );
			$settings = \TYPO3\CMS\Extbase\Utility\ArrayUtility::arrayMergeRecursiveOverrule( $settings, $tsconfig );
		}

		return Base::getConfig( $settings );
	}


	/**
	 * Returns the context item
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContext()
	{
		if( !isset( $this->context ) )
		{
			$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html/templates' );
			$config = $this->getConfig( $this->settings );
			$context = Base::getContext( $config );

			$localI18n = ( isset( $this->settings['i18n'] ) ? $this->settings['i18n'] : array() );
			$locale = $this->getLocale( $context );
			$langid = $locale->getLanguageId();

			$context->setLocale( $locale );
			$context->setI18n( Base::getI18n( array( $langid ), $localI18n ) );
			$context->setView( Base::getView( $context, $this->uriBuilder, $templatePaths, $this->request, $langid ) );

			if( TYPO3_MODE === 'FE' && $GLOBALS['TSFE']->loginUser == 1 )
			{
				$context->setEditor( $GLOBALS['TSFE']->fe_user->user['username'] );
				$context->setUserId( $GLOBALS['TSFE']->fe_user->user[$GLOBALS['TSFE']->fe_user->userid_column] );
				$context->setGroupIds( \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode( ',', $GLOBALS['TSFE']->fe_user->user['usergroup'] ) );
			}

			$this->context = $context;
		}

		return $this->context;
	}


	/**
	 * Returns the locale object for the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	protected function getLocale( \Aimeos\MShop\Context\Item\Iface $context )
	{
		return \Aimeos\Aimeos\Base::getLocale( $context, $this->request );
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
	 * Disables Fluid views for performance reasons.
	 *
	 * return Tx_Extbase_MVC_View_ViewInterface View object
	 */
	protected function resolveView()
	{
		return null;
	}
}
