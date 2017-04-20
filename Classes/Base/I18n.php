<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Aimeos translation class
 *
 * @package TYPO3
 */
class I18n
{
	private static $i18n = array();


	/**
	 * Creates new translation objects.
	 *
     * @param array $i18nPaths Paths to the translation directories
	 * @param array $langIds List of two letter ISO language IDs
	 * @param array $local List of local translation entries overwriting the standard ones
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	public static function get( array $i18nPaths, array $languageIds, array $local = array() )
	{
		$i18nList = array();

		foreach( $languageIds as $langid )
		{
			if( $langid == '' ) { continue; }

			if( !isset( self::$i18n[$langid] ) )
			{
				$i18n = new \Aimeos\MW\Translation\Gettext( $i18nPaths, $langid );

				if( (bool) \Aimeos\Aimeos\Base::getExtConfig( 'useAPC', false ) === true ) {
					$i18n = new \Aimeos\MW\Translation\Decorator\APC( $i18n, \Aimeos\Aimeos\Base::getExtConfig( 'apcPrefix', 't3:' ) );
				}

				self::$i18n[$langid] = $i18n;
			}

			$i18nList[$langid] = self::$i18n[$langid];

			if( isset( $local[$langid] ) )
			{
				$translations = self::parseTranslations( (array) $local[$langid] );
				$i18nList[$langid] = new \Aimeos\MW\Translation\Decorator\Memory( $i18nList[$langid], $translations );
			}
		}

		return $i18nList;
	}


	/**
	 * Parses TypoScript configuration string.
	 *
	 * @param array $entries User-defined translation entries via TypoScript
	 * @return array Associative list of translation domain and original string / list of tranlations
	 */
	protected static function parseTranslations( array $entries )
	{
		$translations = array();

		foreach( $entries as $entry )
		{
			if( isset( $entry['domain'] ) && isset( $entry['string'] ) && isset( $entry['trans'] ) )
			{
				$string = str_replace( ['\\n', '\\'], ["\n", ''], $entry['string'] );
				$trans = array();

				foreach( (array) $entry['trans'] as $str ) {
					$trans[] = str_replace( ['\\n', '\\'], ["\n", ''], $str );
				}

				$translations[$entry['domain']][$string] = $trans;
			}
		}

		return $translations;
	}
}
