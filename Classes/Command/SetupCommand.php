<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2018
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Performs the database initialization and update
 *
 * @package TYPO3
 */
class SetupCommand extends Command
{
	protected static $defaultName = 'aimeos:setup';


	/**
	 * Configures the command name and description.
	 */
	protected function configure()
	{
		$this->setName( self::$defaultName );
		$this->setDescription( 'Initialize or update the Aimeos database tables' );
		$this->addArgument( 'site', InputArgument::OPTIONAL, 'Site for updating database entries', 'default' );
		$this->addArgument( 'tplsite', InputArgument::OPTIONAL, 'Template site for creating or updating database entries', 'default' );
		$this->addOption( 'option', null, InputOption::VALUE_REQUIRED, 'Optional setup configuration, name and value are separated by ":" like "setup/default/demo:1"', array() );
		$this->addOption( 'action', null, InputOption::VALUE_REQUIRED, 'Action name that should be executed, i.e. "migrate", "rollback", "clean"', 'migrate' );
		$this->addOption( 'task', null, InputOption::VALUE_REQUIRED, 'Name of the setup task that should be executed', null );
	}


	/**
	 * Executes the database initialization and update.
	 *
	 * @param InputInterface $input Input object
	 * @param OutputInterface $output Output object
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
        $config = \Aimeos\Aimeos\Base::getConfig();
		$ctx = \Aimeos\Aimeos\Base::getContext( $config );
		$ctx->setEditor( 'aimeos:setup' );

		$config = $ctx->getConfig();
		$site = $input->getArgument( 'site' );
		$tplsite = $input->getArgument( 'tplsite' );

		$config->set( 'setup/site', $site );
		$dbconfig = $this->getDbConfig( $config );
		$this->setOptions( $config, $input );

		$taskPaths = $aimeos->getSetupPaths( $tplsite );
		$manager = new \Aimeos\MW\Setup\Manager\Multiple( $ctx->getDatabaseManager(), $dbconfig, $taskPaths, $ctx );

		$output->writeln( sprintf( 'Initializing or updating the Aimeos database tables for site <info>%1$s</info>', $site ) );

		if( ( $task = $input->getOption( 'task' ) ) && is_array( $task ) ) {
			$task = reset( $task );
		}

		switch( $input->getOption( 'action' ) )
		{
			case 'migrate':
				$manager->migrate( $task );
				break;
			case 'rollback':
				$manager->rollback( $task );
				break;
			case 'clean':
				$manager->clean( $task );
				break;
			default:
				throw new \Exception( sprintf( 'Invalid setup action "%1$s"', $input->getOption( 'action' ) ) );
		}
	}


	/**
	 * Returns the database configuration from the config object.
	 *
	 * @param \Aimeos\MW\Config\Iface $conf Config object
	 * @return array Multi-dimensional associative list of database configuration parameters
	 */
	protected function getDbConfig( \Aimeos\MW\Config\Iface $conf )
	{
		$dbconfig = $conf->get( 'resource', array() );

		foreach( $dbconfig as $rname => $dbconf )
		{
			if( strncmp( $rname, 'db', 2 ) !== 0 ) {
				unset( $dbconfig[$rname] );
			} else {
				$conf->set( "resource/$rname/limit", 3 );
			}
		}

		return $dbconfig;
	}


	/**
	 * Extracts the configuration options from the input object and updates the configuration values in the config object.
	 *
	 * @param \Aimeos\MW\Config\Iface $conf Configuration object
	 * @param InputInterface $input Input object
	 * @param array Associative list of database configurations
	 * @throws \RuntimeException If the format of the options is invalid
	 */
	protected function setOptions( \Aimeos\MW\Config\Iface $conf, InputInterface $input )
	{
		foreach( (array) $input->getOption( 'option' ) as $option )
		{
			list( $name, $value ) = explode( ':', $option );
			$conf->set( str_replace( '\\', '/', $name ), $value );
		}
	}
}
