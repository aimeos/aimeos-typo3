<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2015
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
	/**
	 * Execute the list of jobs for the given sites
	 *
	 * @param array $conf Multi-dimensional array of configuration options
	 * @param array $jobs List of job names
	 * @param string $sites List of site names
	 */
	public static function execute( array $conf, array $jobs, $sites )
	{
		$aimeos = Aimeos\Base::getAimeos();
		$context = self::getContext( $conf );
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		foreach( self::getSiteItems( $context, $sites ) as $siteItem )
		{
			$localeItem = $manager->bootstrap( $siteItem->getCode(), '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );

			$context->setLocale( $localeItem );

			foreach( $jobs as $jobname ) {
				\Aimeos\Controller\Jobs\Factory::createController( $context, $aimeos, $jobname )->run();
			}
		}
	}


	/**
	 * Returns the current context.
	 *
	 * @param array Multi-dimensional associative list of key/value pairs
	 * @return MShop_Context_Item_Interface Context object
	 */
	public static function getContext( array $conf = array() )
	{
		$aimeos = Aimeos\Base::getAimeos();
		$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/templates' );
		$tmplPaths = array_merge( $tmplPaths, $aimeos->getCustomPaths( 'client/html' ) );

		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Extbase\Object\ObjectManager' );
		$uriBuilder = $objectManager->get( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' );
		$uriBuilder->setArgumentPrefix( 'ai' );

		$config = Aimeos\Base::getConfig( $conf );
		$context = Aimeos\Base::getContext( $config );

		$langManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/language' );
		$search = $langManager->createSearch( true );

		$expr = array();
		$expr[] = $search->getConditions();
		$expr[] = $search->compare( '==', 'locale.language.id', 'en' ); // default language

		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) { // BE language
			$expr[] = $search->compare( '==', 'locale.language.id', $GLOBALS['BE_USER']->uc['lang'] );
		}

		$search->setConditions( $search->combine( '||', $expr ) );
		$langids = array_keys( $langManager->searchItems( $search ) );

		$i18n = Aimeos\Base::getI18n( $langids, ( isset( $conf['i18n'] ) ? (array) $conf['i18n'] : array() ) );
		$context->setI18n( $i18n );

		$view = Aimeos\Base::getView( $config, $uriBuilder, $tmplPaths );
		$context->setView( $view );

		$context->setEditor( 'scheduler' );

		return $context;
	}


	/**
	 * Returns the enabled site items which may be limited by the input arguments.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @param string $sites Unique site codes
	 * @return \Aimeos\MShop\Locale\Item\Site\Iface[] List of site items
	 */
	public static function getSiteItems( \Aimeos\MShop\Context\Item\Iface $context, $sites )
	{
		if( !is_array( $sites )  ) {
			$sites = explode( ' ', $sites );
		}

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'locale/site' );
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
			$SERVER['HTTP_HOST'] = $host;
		}
	}
}
