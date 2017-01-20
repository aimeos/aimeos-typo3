<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Scheduler;


use Aimeos\Aimeos;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos common scheduler class.
 *
 * @package TYPO3
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


		$tmplPaths = Aimeos\Base::getAimeos()->getCustomPaths( 'controller/jobs/templates' );
		$view = Aimeos\Base::getView( $config, self::getUriBuilder(), $tmplPaths );
		$context->setView( $view );

		$context->setEditor( 'scheduler' );

		return $context;
	}


	public static function getUriBuilder()
	{
		$objectManager = GeneralUtility::makeInstance( 'TYPO3\CMS\Extbase\Object\ObjectManager' );

		$contentObjectRenderer = $objectManager->get( 'TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer' );
		$configurationManager = $objectManager->get( 'TYPO3\CMS\Extbase\Configuration\ConfigurationManager' );
		$uriBuilder = $objectManager->get( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' );

		$configurationManager->setContentObject($contentObjectRenderer);
		$uriBuilder->injectConfigurationManager($configurationManager);
		$uriBuilder->setArgumentPrefix( 'ai' );

		return $uriBuilder;
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
		if( !is_object( $GLOBALS['TT'] ) )
		{
			$GLOBALS['TT'] = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\TimeTracker\TimeTracker' );
			$GLOBALS['TT']->start();
		}

		$page = GeneralUtility::makeInstance( 'TYPO3\CMS\Frontend\Page\PageRepository' );
		$page->init( true );

		$name = 'TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController';
		$tsfe = GeneralUtility::makeInstance( $name,  $GLOBALS['TYPO3_CONF_VARS'], $pageid, 0 );
		$tsfe->connectToDB();
		$tsfe->initFEuser();
		$tsfe->no_cache = true;
		$tsfe->sys_page = $page;
		$tsfe->rootLine = $page->getRootLine( $pageid );
		$tsfe->determineId();
		$tsfe->initTemplate();
		$tsfe->getConfigArray();

		$GLOBALS['TSFE'] = $tsfe;

		if( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded( 'realurl' ) )
		{
			$rootline = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine( $pageid );
			$host = \TYPO3\CMS\Backend\Utility\BackendUtility::firstDomainRecord( $rootline );
			$SERVER['HTTP_HOST'] = $host;
		}
	}
}
