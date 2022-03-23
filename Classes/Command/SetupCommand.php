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
	/**
	 * Configures the command name and description.
	 */
	protected function configure()
	{
		$this->setName( 'aimeos:setup' );
		$this->setDescription( 'Initialize or update the Aimeos database tables' );
		$this->addArgument( 'site', InputArgument::OPTIONAL, 'Site for updating database entries', 'default' );
		$this->addArgument( 'tplsite', InputArgument::OPTIONAL, 'Template site for creating or updating database entries', 'default' );
		$this->addOption( 'option', null, InputOption::VALUE_REQUIRED, 'Optional setup configuration, name and value are separated by ":" like "setup/default/demo:1"', [] );
		$this->addOption( 'v', null, InputOption::VALUE_OPTIONAL, 'Verbosity level, "v", "vv" or "vvv"', 'vv' );
		$this->addOption( 'q', null, InputOption::VALUE_NONE, 'Quiet mode without any output' );
	}


	/**
	 * Executes the database initialization and update.
	 *
	 * @param InputInterface $input Input object
	 * @param OutputInterface $output Output object
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		\Aimeos\MShop::cache( false );
		\Aimeos\MAdmin::cache( false );

		$site = $input->getArgument( 'site' );
		$template = $input->getArgument( 'tplsite' );

		$config = \Aimeos\Aimeos\Base::config();
		$boostrap = \Aimeos\Aimeos\Base::aimeos();
		$ctx = \Aimeos\Aimeos\Base::context( $config )->setEditor( 'aimeos:setup' );

		$output->writeln( sprintf( 'Initializing or updating the Aimeos database tables for site <info>%1$s</info>', $site ) );

		\Aimeos\Setup::use( $boostrap )
			->context( $this->addConfig( $ctx->setEditor( 'aimeos:setup' ), $input->getOption( 'option' ) ) )
			->verbose( $input->getOption( 'q' ) ? '' : $input->getOption( 'v' ) )
			->up( $site, $template );

		return 0;
	}


	/**
	 * Adds the configuration options from the input object to the given context
	 *
	 * @param array|string $options Input object
	 * @param \Aimeos\MShop\ContextIface $ctx Context object
	 * @return array Associative list of key/value pairs of configuration options
	 */
	protected function addConfig( \Aimeos\MShop\ContextIface $ctx, $options ) : \Aimeos\MShop\ContextIface
	{
		$config = $ctx->config();

		foreach( (array) $options as $option )
		{
			list( $name, $value ) = explode( ':', $option );
			$config->set( $name, $value );
		}

		return $ctx;
	}
}
