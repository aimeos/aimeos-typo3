<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Scheduler;


use Aimeos\Aimeos;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos common scheduler class.
 *
 * @package TYPO3
 */
class Base
{
	protected static $router;


	/**
	 * Execute the list of jobs for the given sites
	 *
	 * @param array $conf Multi-dimensional array of configuration options
	 * @param array $jobs List of job names
	 * @param array|string $sites List of site names
	 */
	public static function execute( array $conf, array $jobs, $sites, ?string $pid = null )
	{
		$aimeos = Aimeos\Base::getAimeos();
		$context = self::getContext( $conf, $pid );
		$process = $context->getProcess();

		// Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
		if( method_exists( '\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections' ) ) {
			 GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Database\ConnectionPool' )->resetConnections();
		}

		$manager = \Aimeos\MShop::create( $context, 'locale' );

		foreach( self::getSiteItems( $context, $sites ) as $siteItem )
		{
			\Aimeos\MShop::cache( true );
			\Aimeos\MAdmin::cache( true );

			$localeItem = $manager->bootstrap( $siteItem->getCode(), '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );

			$context->setLocale( $localeItem );

			foreach( $jobs as $jobname )
			{
				$fcn = function( $context, $aimeos, $jobname ) {
					\Aimeos\Controller\Jobs::create( $context, $aimeos, $jobname )->run();
				};

				$process->start( $fcn, [$context, $aimeos, $jobname], false );
			}
		}

		$process->wait();
	}


	/**
	 * Returns the current context.
	 *
	 * @param array Multi-dimensional associative list of key/value pairs
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function getContext( array $conf = [], ?string $pid = null ) : \Aimeos\MShop\Context\Item\Iface
	{
		$config = Aimeos\Base::getConfig( $conf );
		$context = Aimeos\Base::getContext( $config );


		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );
		$search = $langManager->filter( true );

		$expr = [];
		$expr[] = $search->getConditions();
		$expr[] = $search->compare( '==', 'locale.language.id', 'en' ); // default language

		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) { // BE language
			$expr[] = $search->compare( '==', 'locale.language.id', $GLOBALS['BE_USER']->uc['lang'] );
		}

		$search->setConditions( $search->combine( '||', $expr ) );
		$langids = $langManager->search( $search )->keys()->toArray();

		$context->setI18n( Aimeos\Base::getI18n( $langids, (array) ( $conf['i18n'] ?? [] ) ) );

		$tmplPaths = Aimeos\Base::getAimeos()->getTemplatePaths( 'controller/jobs/templates' );
		$context->setView( Aimeos\Base::getView( $context, self::getRouter( $pid ), $tmplPaths ) );

		$context->setEditor( 'scheduler' );

		return $context;
	}


	/**
	 * Returns the enabled site items which may be limited by the input arguments.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @param array|string $sites Unique site codes
	 * @return \Aimeos\Map List of site items implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 */
	public static function getSiteItems( \Aimeos\MShop\Context\Item\Iface $context, $sites ) : \Aimeos\Map
	{
		if( !is_array( $sites ) ) {
			$sites = explode( ' ', $sites );
		}

		$manager = \Aimeos\MShop::create( $context, 'locale/site' );
		$search = $manager->filter();

		if( !empty( $sites ) ) {
			$search->setConditions( $search->compare( '==', 'locale.site.code', $sites ) );
		}

		return $manager->search( $search );
	}


	/**
	 * Returns the page router
	 *
	 * @param string|null $pid Page ID
	 * @return \TYPO3\CMS\Core\Routing\RouterInterface Page router
	 * @throws \RuntimeException If no site configuraiton is available
	 */
	protected static function getRouter( ?string $pid ) : \TYPO3\CMS\Core\Routing\RouterInterface
	{
		$siteFinder = GeneralUtility::makeInstance( SiteFinder::class );
		$site = $pid ? $siteFinder->getSiteByPageId( $pid ) : current( $siteFinder->getAllSites() );

		if( $site ) {
			return $site->getRouter();
		}

		throw new \RuntimeException( 'No site configuration found' );
	}
}
