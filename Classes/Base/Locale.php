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
	 * Returns the locale object for the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public function get( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		if( !isset( self::$locale ) )
		{
			$session = $context->getSession();
			$config = $context->getConfig();


			$sitecode = $config->get( 'mshop/locale/site', 'default' );
			$name = $config->get( 'typo3/param/name/site', 'loc_site' );

			if( $request !== null && $request->hasArgument( $name ) === true ) {
				$sitecode = $request->getArgument( $name );
			} elseif( ( $value = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP( 'S' ) ) !== null ) {
				$sitecode = $value;
			}


			$langid = $config->get( 'mshop/locale/language', '' );
			$name = $config->get( 'typo3/param/name/language', 'loc_language' );

			if( $request !== null && $request->hasArgument( $name ) === true ) {
				$langid = $request->getArgument( $name );
			} elseif( isset( $GLOBALS['TSFE']->config['config']['language'] ) ) {
				$langid = $GLOBALS['TSFE']->config['config']['language'];
			}


			$currency = $config->get( 'mshop/locale/currency', '' );
			$name = $config->get( 'typo3/param/name/currency', 'loc_currency' );

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
}
