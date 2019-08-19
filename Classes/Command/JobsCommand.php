<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2018-2019
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Command;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Console\Command\Command;
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
	}


	/**
	 * Executes the job controllers
	 *
	 * @param InputInterface $input Input object
	 * @param OutputInterface $output Output object
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$context = $this->getContext();
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
	}


	/**
	 * Returns a context object
	 *
	 * @return \Aimeos\MShop\Context\Item\Standard Context object containing only the most necessary dependencies
	 */
	protected function getContext()
	{
		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
		$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/templates' );

		$config = \Aimeos\Aimeos\Base::getConfig();
		$context = \Aimeos\Aimeos\Base::getContext( $config );

		$langManager = \Aimeos\MShop::create( $context, 'locale/language' );
		$langids = array_keys( $langManager->searchItems( $langManager->createSearch( true ) ) );

		$i18n = \Aimeos\Aimeos\Base::getI18n( $langids, $config->get( 'i18n', [] ) );
		$context->setI18n( $i18n );

		$view = \Aimeos\Aimeos\Base::getView( $context, $this->getUriBuilder(), $tmplPaths );
		$context->setView( $view );

		return $context->setEditor( 'aimeos:jobs' );
	}


	/**
	 * Returns the enabled site items which may be limited by the input arguments.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @param InputInterface $input Input object
	 * @return \Aimeos\MShop\Locale\Item\Site\Interface[] List of site items
	 */
	protected function getSiteItems( \Aimeos\MShop\Context\Item\Iface $context, InputInterface $input )
	{
		$manager = \Aimeos\MShop::create( $context, 'locale/site' );
		$search = $manager->createSearch();

		if( ( $codes = (string) $input->getArgument( 'site' ) ) !== '' ) {
			$search->setConditions( $search->compare( '==', 'locale.site.code', explode( ' ', $codes ) ) );
		}

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the URI builder object
	 *
	 * @return TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder URI builder
	 */
	protected function getUriBuilder()
	{
		$objectManager = GeneralUtility::makeInstance( 'TYPO3\CMS\Extbase\Object\ObjectManager' );

		$contentObjectRenderer = $objectManager->get( 'TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer' );
		$configurationManager = $objectManager->get( 'TYPO3\CMS\Extbase\Configuration\ConfigurationManager' );
		$configurationManager->setContentObject( $contentObjectRenderer );

		$uriBuilder = $objectManager->get( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' );

		if( method_exists( $uriBuilder, 'injectConfigurationManager' ) === false )
		{
			$class = 'TYPO3\\CMS\\Extbase\\Reflection\\PropertyReflection';
			$prop = GeneralUtility::makeInstance( $class, $uriBuilder, 'configurationManager' );

			$prop->setAccessible( true );
			$prop->setValue( $uriBuilder, $configurationManager );
		}
		else
		{
			$uriBuilder->injectConfigurationManager( $configurationManager );
		}

		$uriBuilder->initializeObject();
		$uriBuilder->setArgumentPrefix( 'ai' );

		return $uriBuilder;
	}
}
