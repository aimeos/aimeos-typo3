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
	 * Execute the list of jobs for the given sites
	 *
	 * @param \MShop_Context_Item_Interface $context Context item
	 * @param array $jobs List of job names
	 * @param string $sites List of site names
	 */
	public static function execute( \MShop_Context_Item_Interface $context, array $jobs, $sites )
	{
		$aimeos = Aimeos\Base::getAimeos();
		$manager = \MShop_Factory::createManager( $context, 'locale' );

		foreach( self::getSiteItems( $context, $sites ) as $siteItem )
		{
			$localeItem = $manager->bootstrap( $siteItem->getCode(), '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );

			$context->setLocale( $localeItem );

			foreach( $jobs as $jobname ) {
				\Controller_Jobs_Factory::createController( $context, $aimeos, $jobname )->run();
			}
		}
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
			$aimeos = Aimeos\Base::getAimeos();
			$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/layouts' );
			$tmplPaths = array_merge( $tmplPaths, $aimeos->getCustomPaths( 'client/html' ) );

			$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Extbase\Object\ObjectManager::class );
			$uriBuilder = $objectManager->get( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' );
			$uriBuilder->setArgumentPrefix( 'ai' );

			$config = Aimeos\Base::getConfig( $localConf );
			$context = Aimeos\Base::getContext( $config );

			$langManager = \MShop_Locale_Manager_Factory::createManager( $context )->getSubManager( 'language' );
			$langids = array_keys( $langManager->searchItems( $langManager->createSearch( true ) ) );

			$i18n = Aimeos\Base::getI18n( $langids, ( isset( $conf['i18n'] ) ? (array) $conf['i18n'] : array() ) );
			$context->setI18n( $i18n );

			$view = Aimeos\Base::getView( $config, $uriBuilder, $tmplPaths );
			$context->setView( $view );

			$context->setEditor( 'scheduler' );

			self::$_context = $context;
		}

		return self::$_context;
	}


	/**
	 * Returns the enabled site items which may be limited by the input arguments.
	 *
	 * @param \MShop_Context_Item_Interface $context Context item object
	 * @param string $sites Unique site codes
	 * @return \MShop_Locale_Item_Site_Interface[] List of site items
	 */
	public static function getSiteItems( \MShop_Context_Item_Interface $context, $sites )
	{
		if( !is_array( $sites )  ) {
			$sites = explode( ' ', $sites );
		}

		$manager = \MShop_Factory::createManager( $context, 'locale/site' );
		$search = $manager->createSearch();

		if( !empty( $sites ) ) {
			$search->setConditions( $search->compare( '==', 'locale.site.code', $sites ) );
		}

		return $manager->searchItems( $search );
	}


	/**
	 * Initializes the frontend to render frontend links in scheduler tasks
	 *
	 * @param integer $pageid Page ID for the frontend configuration
	 */
	public static function initFrontend( $pageid )
	{
		$type = 0;
		$name = 'TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController';

		if( !is_object( $GLOBALS['TT'] ) )
		{
			$GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker();
			$GLOBALS['TT']->start();
		}

		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $name,  $GLOBALS['TYPO3_CONF_VARS'], $pageid, $type );
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();

		if( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded( 'realurl' ) )
		{
			$rootline = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine( $pageid );
			$host = \TYPO3\CMS\Backend\Utility\BackendUtility::firstDomainRecord( $rootline );
			$_SERVER['HTTP_HOST'] = $host;
		}
	}
}
