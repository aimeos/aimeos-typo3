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
     * @param array $tsconf Multi-dimensional array of configuration options (replaced)
     * @param array $conf Multi-dimensional array of configuration options (merged)
     * @param array $jobs List of job names
     * @param array|string $sites List of site names
     */
    public static function execute( array $tsconf, array $conf, array $jobs, $sites, ?string $pid = null )
    {
        $aimeos = Aimeos\Base::aimeos();
        $context = self::context( $tsconf, $pid );
        $context->config()->apply( $conf );
        $process = $context->process();

        // Reset before child processes are spawned to avoid lost DB connections afterwards
        GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Database\ConnectionPool' )->resetConnections();

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
     * @param array $tsconf Multi-dimensional associative list of key/value pairs
     * @return \Aimeos\MShop\ContextIface Context object
     */
    public static function context( array $tsconf = [], ?string $pid = null ) : \Aimeos\MShop\ContextIface
    {
        $config = Aimeos\Base::config( $tsconf );
        $context = Aimeos\Base::context( $config );


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

        $context->setI18n( Aimeos\Base::i18n( $langids, (array) ( $tsconf['i18n'] ?? [] ) ) );

        $tmplPaths = Aimeos\Base::aimeos()->getTemplatePaths( 'controller/jobs/templates' );
        $context->setView( Aimeos\Base::view( $context, self::getRouter( $pid ), $tmplPaths ) );

        $context->setEditor( 'scheduler' );

        return $context;
    }


    /**
     * Returns the enabled site items which may be limited by the input arguments.
     *
     * @param \Aimeos\MShop\ContextIface $context Context item object
     * @param array|string $sites Unique site codes
     * @return \Aimeos\Map List of site items implementing \Aimeos\MShop\Locale\Item\Site\Iface
     */
    public static function getSiteItems( \Aimeos\MShop\ContextIface $context, $sites ) : \Aimeos\Map
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
