<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2018-2019
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Command;


use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Executes the job controllers
 *
 * @package TYPO3
 */
class JobsCommand extends Command
{
	protected static $defaultName = 'aimeos:jobs';


	/**
	 * Configures the command name and description
	 */
	protected function configure()
	{
		$this->setName( self::$defaultName );
		$this->setDescription( 'Executes the job controllers' );
		$this->addArgument( 'jobs', InputArgument::REQUIRED, 'One or more job controller names like "admin/job customer/email/watch"' );
		$this->addArgument( 'site', InputArgument::OPTIONAL, 'Site codes to execute the jobs for like "default unittest" (none for all)' );
		$this->addOption( 'pid', null, InputOption::VALUE_REQUIRED, 'Page ID of the catalog detail page for jobs generating URLs' );
	}


	/**
	 * Executes the job controllers
	 *
	 * @param InputInterface $input Input object
	 * @param OutputInterface $output Output object
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$context = $this->getContext( $input->getOption( 'pid' ) );
		$process = $context->getProcess();

		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
		$jobs = explode( ' ', $input->getArgument( 'jobs' ) );
		$localeManager = \Aimeos\MShop::create( $context, 'locale' );

		foreach( $this->getSiteItems( $context, $input ) as $siteItem )
		{
			$localeItem = $localeManager->bootstrap( $siteItem->getCode(), '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );
			$context->setLocale( $localeItem );

			$config = $context->getConfig();
			foreach( $localeItem->getSiteItem()->getConfig() as $key => $value ) {
				$config->set( $key, $value );
			}

			$output->writeln( sprintf( 'Executing the Aimeos jobs for "<info>%s</info>"', $siteItem->getCode() ) );

			// Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
			if( method_exists( '\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections' ) ) {
				GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Database\ConnectionPool' )->resetConnections();
			}

			foreach( $jobs as $jobname )
			{
				$fcn = function( $context, $aimeos, $jobname ) {
					\Aimeos\Controller\Jobs::create( $context, $aimeos, $jobname )->run();
				};

				$process->start( $fcn, [$context, $aimeos, $jobname], true );
			}
		}

		$process->wait();

		return 0;
	}


	/**
	 * Returns a context object
	 *
	 * @param string|null $pid Page ID if available
	 * @return \Aimeos\MShop\Context\Item\Iface Context object containing only the most necessary dependencies
	 */
	protected function getContext( ?string $pid ) : \Aimeos\MShop\Context\Item\Iface
	{
		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
		$tmplPaths = $aimeos->getTemplatePaths( 'controller/jobs/templates' );

		$config = \Aimeos\Aimeos\Base::getConfig();
		$context = \Aimeos\Aimeos\Base::getContext( $config );

		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );
		$langids = $langManager->search( $langManager->filter( true ) )->keys()->toArray();

		$i18n = \Aimeos\Aimeos\Base::getI18n( $langids, $config->get( 'i18n', [] ) );
		$context->setI18n( $i18n );

		$view = \Aimeos\Aimeos\Base::getView( $context, $this->getRouter( $pid ), $tmplPaths );
		$context->setView( $view );

		$context->setSession( new \Aimeos\MW\Session\None() );
		$context->setCache( new \Aimeos\MW\Cache\None() );

		return $context->setEditor( 'aimeos:jobs' );
	}


	/**
	 * Returns the enabled site items which may be limited by the input arguments.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @param InputInterface $input Input object
	 * @return \Aimeos\Map List of site items implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 */
	protected function getSiteItems( \Aimeos\MShop\Context\Item\Iface $context, InputInterface $input ) : \Aimeos\Map
	{
		$manager = \Aimeos\MShop::create( $context, 'locale/site' );
		$search = $manager->filter();

		if( ( $codes = (string) $input->getArgument( 'site' ) ) !== '' ) {
			$search->setConditions( $search->compare( '==', 'locale.site.code', explode( ' ', $codes ) ) );
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
	protected function getRouter( ?string $pid ) : \TYPO3\CMS\Core\Routing\RouterInterface
	{
		$siteFinder = GeneralUtility::makeInstance( SiteFinder::class );
		$site = $pid ? $siteFinder->getSiteByPageId( $pid ) : current( $siteFinder->getAllSites() );

		if( $site ) {
			return $site->getRouter();
		}

		throw new \RuntimeException( 'No site configuration found' );
	}
}
