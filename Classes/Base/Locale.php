<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Aimeos locale class
 *
 * @package TYPO3
 */
class Locale
{
	private static $locale;


	/**
	 * Returns the locale object for frontend
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function get( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		if( !isset( self::$locale ) )
		{
			$config = $context->getConfig();


			$sitecode = $config->get( 'mshop/locale/site', 'default' );
			$name = $config->get( 'typo3/param/name/site', 'site' );

			if( $request !== null && $request->hasArgument( $name ) === true ) {
				$sitecode = $request->getArgument( $name );
			} elseif( ( $value = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP( 'S' ) ) !== null ) {
				$sitecode = $value;
			}


			$langid = $config->get( 'mshop/locale/language', '' );
			$name = $config->get( 'typo3/param/name/language', 'locale' );

			if( $request !== null && $request->hasArgument( $name ) === true ) {
				$langid = $request->getArgument( $name );
			} elseif( isset( $GLOBALS['TSFE']->config['config']['language'] ) ) {
				$langid = $GLOBALS['TSFE']->config['config']['language'];
			}


			$currency = $config->get( 'mshop/locale/currency', '' );
			$name = $config->get( 'typo3/param/name/currency', 'currency' );

			if( $request !== null && $request->hasArgument( $name ) === true ) {
				$currency = $request->getArgument( $name );
			} elseif( ( $value = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP( 'C' ) ) !== null ) {
				$currency = $value;
			}


			$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $context );
			self::$locale = $localeManager->bootstrap( $sitecode, $langid, $currency );
		}

		return self::$locale;
	}


	/**
	 * Returns the locale item for the backend
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $site Unique site code
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	public static function getBackend( \Aimeos\MShop\Context\Item\Iface $context, $site )
	{
		$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		try {
			$localeItem = $localeManager->bootstrap( $site, '', '', false, null, true );
		} catch( \Aimeos\MShop\Exception $e ) {
			$localeItem = $localeManager->createItem();
		}

		return $localeItem;
	}
}
