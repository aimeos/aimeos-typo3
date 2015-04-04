<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */

namespace Aimeos\Aimeos\Scheduler;


use Aimeos\Aimeos;


/**
 * Aimeos common scheduler class.
 *
 * @package TYPO3_Aimeos
 */
class Base
{
	static private $_context;


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param MW_Config_Interface $config Configuration object
	 * @return MW_View_Interface View object
	 */
	public static function createView( \MW_Config_Interface $config )
	{
		$aimeos = Aimeos\Base::getAimeos();
		$templatePaths = $aimeos->getCustomPath( 'controller/jobs/layouts' );

		$view = new \MW_View_Default();

		$helper = new \MW_View_Helper_Config_Default( $view, $config );
		$view->addHelper( 'config', $helper );

		$helper = new \MW_View_Helper_Partial_Default( $view, $config, $templatePaths );
		$view->addHelper( 'partial', $helper );

		$helper = new \MW_View_Helper_Parameter_Default( $view, array() );
		$view->addHelper( 'param', $helper );

		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$helper = new \MW_View_Helper_Number_Default( $view, $sepDec, $sep1000 );
		$view->addHelper( 'number', $helper );

		$helper = new \MW_View_Helper_Url_None( $view );
		$view->addHelper( 'url', $helper );

		$helper = new \MW_View_Helper_FormParam_Default( $view, array( 'ai' ) );
		$view->addHelper( 'formparam', $helper );

		$helper = new \MW_View_Helper_Encoder_Default( $view );
		$view->addHelper( 'encoder', $helper );

		return $view;
	}


	/**
	 * Executes the jobs.
	 *
	 * @param array $sitecodes List of site codes
	 * @param array $controllers List of controller names
	 * @param string $tsconfig TypoScript configuration string
	 * @param string $langid Two letter ISO language code of the backend user
	 * @throws Controller_Jobs_Exception If a job can't be executed
	 * @throws MShop_Exception If an error in a manager occurs
	 * @throws MW_DB_Exception If a dataAimeos\Base error occurs
	 */
	public static function execute( array $sitecodes, array $controllers, $tsconfig, $langid )
	{
		$conf = Aimeos\Base::parseTS( $tsconfig );
		$context = self::getContext( $conf );
		$aimeos = Aimeos\Base::getAimeos();

		$manager = \MShop_Locale_Manager_Factory::createManager( $context );

		foreach( $sitecodes as $sitecode )
		{
			$localeItem = $manager->bootstrap( $sitecode, $langid, '', false );
			$context->setLocale( $localeItem );

			foreach( (array) $controllers as $name ) {
				\Controller_Jobs_Factory::createController( $context, $aimeos, $name )->run();
			}
		}

		return true;
	}


	/**
	 * Returns the current context.
	 *
	 * @param array Multi-dimensional associative list of key/value pairs
	 * @return MShop_Context_Item_Interface Context object
	 */
	public static function getContext( array $localConf = array() )
	{
		if( self::$_context === null )
		{
			// Important! Sets include paths
			$aimeos = Aimeos\Base::getAimeos();
			$context = new \MShop_Context_Item_Default();


			$conf = Aimeos\Base::getConfig( $localConf );
			$context->setConfig( $conf );

			$dbm = new \MW_DB_Manager_PDO( $conf );
			$context->setDataBaseManager( $dbm );

			$cache = new \MW_Cache_None();
			$context->setCache( $cache );

			$logger = \MAdmin_Log_Manager_Factory::createManager( $context );
			$context->setLogger( $logger );

			$mail = new \MW_Mail_Typo3( \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Mail\\MailMessage' ) );
			$context->setMail( $mail );

			$i18n = self::_createI18n( $context, $aimeos->getI18nPaths() );
			$context->setI18n( $i18n );

			$view = self::createView( $conf );
			$context->setView( $view );

			$context->setEditor( 'scheduler' );

			$localeManager = \MShop_Locale_Manager_Factory::createManager( $context );
			$localeItem = $localeManager->createItem();
			$localeItem->setLanguageId( 'en' );
			$context->setLocale( $localeItem );


			self::$_context = $context;
		}

		return self::$_context;
	}


	/**
	 * Creates new translation objects.
	 *
	 * @param MShop_Context_Item_Interface $context Context object
	 * @param array List of paths to the i18n files
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	protected static function _createI18n( \MShop_Context_Item_Interface $context, array $i18nPaths )
	{
		$list = array();
		$config = $context->getConfig();
		$langManager = \MShop_Locale_Manager_Factory::createManager( $context )->getSubManager( 'language' );

		foreach( $langManager->searchItems( $langManager->createSearch( true ) ) as $id => $langItem )
		{
			$i18n = new \MW_Translation_Zend2( $i18nPaths, 'gettext', $id, array( 'disableNotices' => true ) );

			if( ( $entries = $config->get( 'i18n/' . $id ) ) !== null )
			{
				$translations = Aimeos\Base::parseTranslations( (array) $entries );
				$i18n = new \MW_Translation_Decorator_Memory( $i18n, $translations );
			}

			$list[$id] = $i18n;
		}

		return $list;
	}
}
