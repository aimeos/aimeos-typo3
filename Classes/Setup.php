<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


use \Symfony\Component\Console\Output\OutputInterface;
use \TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use \TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;
use \TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use \TYPO3\CMS\Install\Updates\RepeatableInterface;
use \TYPO3\CMS\Install\Updates\ChattyInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos setup class.
 *
 * @package TYPO3
 */
class Setup implements UpgradeWizardInterface, RepeatableInterface, ChattyInterface
{
	private $output;


	/**
	 * Return the identifier for this wizard
	 * This should be the same string as used in the ext_localconf class registration
	 *
	 * @return string
	 */
	public function getIdentifier() : string
	{
	  return 'aimeos';
	}


	/**
	 * Return the speaking name of this wizard
	 *
	 * @return string
	 */
	public function getTitle() : string
	{
	  return 'Aimeos database migration';
	}


	/**
	 * Return the description for this wizard
	 *
	 * @return string
	 */
	public function getDescription() : string
	{
	  return 'Updates the Aimeos database tables and migrates data if necessary';
	}


	/**
	 * Execute the update
	 *
	 * @return bool
	 */
	public function executeUpdate() : bool
	{
		try
		{
			ob_start();
			$exectimeStart = microtime( true );

			self::execute();

			$this->output->writeln( ob_get_clean() );
			$this->output->writeln( sprintf( 'Setup process lasted %1$f sec', ( microtime( true ) - $exectimeStart ) ) );
		}
		catch( \Throwable $t )
		{
			$this->output->writeln( ob_get_clean() );
			$this->output->writeln( $t->getMessage() );
			$this->output->writeln( $t->getTraceAsString() );

			return false;
		}

		return true;
	}


	/**
	 * Returns the classes the upgrade wizard depends on
	 *
	 * @return string[]
	 */
	public function getPrerequisites() : array
	{
		return [];
	}


	/**
	 * Setter injection for output into upgrade wizards
	 *
	 * @param OutputInterface $output
	 */
	public function setOutput( OutputInterface $output ) : void
	{
		$this->output = $output;
	}


	/**
	 * Checks if  update is necessary
	 *
	 * @return bool Whether an update is required (TRUE) or not (FALSE)
	 */
	public function updateNecessary() : bool
	{
		return true;
	}


	/**
	 * Executes the setup tasks for updating the database.
	 */
	public static function execute()
	{
		ini_set( 'max_execution_time', 0 );

		$ctx = self::context();
		$aimeos = \Aimeos\Aimeos\Base::aimeos();
		$sitecode = \Aimeos\Aimeos\Base::getExtConfig( 'siteCode', 'default' );
		$siteTpl = \Aimeos\Aimeos\Base::getExtConfig( 'siteTpl', 'default' );
		$taskPaths = $aimeos->getSetupPaths( $siteTpl );

		$dbm = $ctx->getDatabaseManager();
		$config = $ctx->config();

		$config->set( 'setup/site', $sitecode );

		$dbconfig = $config->get( 'resource', [] );

		foreach( $dbconfig as $rname => $dbconf )
		{
			if( strncmp( $rname, 'db', 2 ) !== 0 ) {
				unset( $dbconfig[$rname] );
			} else {
				$config->set( "resource/$rname/limit", 5 );
			}
		}


		$class = \TYPO3\CMS\Extbase\Object\ObjectManager::class;
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $class );

		$object = $objectManager->get( 'TYPO3\CMS\Core\Configuration\ExtensionConfiguration' );
		$demo = $object->get( 'aimeos', 'useDemoData' );

		$local = ['setup' => ['default' => ['demo' => (string) $demo]]];
		$ctx->setConfig( new \Aimeos\MW\Config\Decorator\Memory( $config, $local ) );

		$manager = new \Aimeos\MW\Setup\Manager\Multiple( $dbm, $dbconfig, $taskPaths, $ctx );
		$manager->migrate();


		if( defined( 'TYPO3_version' ) && version_compare( constant( 'TYPO3_version' ), '11.0.0', '<' ) ) {
			$object->set( 'aimeos', 'useDemoData', '' );
		} else {
			$object->set( 'aimeos', ['useDemoData' => ''] );
		}
	}


	/**
	 * Returns the current schema for the install tool
	 *
	 * @param array $sql List of SQL statements
	 * @return array SQL statements required for the install tool
	 */
	public static function schema( array $sql ) : array
	{
		$ctx = self::context();
		$dbm = $ctx->getDatabaseManager();
		$conn = $dbm->acquire();

		try
		{
			$tables = [];

			foreach( ['fe_users_', 'madmin_', 'mshop_'] as $prefix )
			{
				$result = $conn->create( 'SHOW TABLES like \'' . $prefix . '%\'' )->execute();

				while( ( $row = $result->fetch( \Aimeos\MW\DB\Result\Base::FETCH_NUM ) ) !== null ) {
					$tables[] = $row[0];
				}
			}

			foreach( $tables as $table )
			{
				$result = $conn->create( 'SHOW CREATE TABLE ' . $table )->execute();

				while( ( $row = $result->fetch( \Aimeos\MW\DB\Result\Base::FETCH_NUM ) ) !== null )
				{
					$str = preg_replace( '/,[\n ]*CONSTRAINT.+CASCADE/', '', $row[1] );
					$str = str_replace( '"', '`', $str );

					$sql[] = $str . ";\n";
				}
			}

			$dbm->release( $conn );
		}
		catch( \Exception $e )
		{
			$dbm->release( $conn );
		}


		return ['sqlString' => $sql];
	}


	/**
	 * For existing installations
	 *
	 * @param string|null $extname Installed extension name
	 */
	public static function executeOnSignal( string $extname = null )
	{
		self::signal( $extname );
	}


	/**
	 * Update schema if extension is installed
	 *
	 * @param string|null $extname Installed extension name
	 */
	public static function signal( string $extname = null )
	{
		if( $extname === 'aimeos' && \Aimeos\Aimeos\Base::getExtConfig( 'autoSetup', true ) ) {
			self::execute();
		}
	}


	/**
	 * Alter schema to avoid TYPO3 dropping Aimeos tables
	 *
	 * @param AlterTableDefinitionStatementsEvent $event Event object
	 */
	public function schemaEvent( AlterTableDefinitionStatementsEvent $event )
	{
		$list = self::schema( [] );

		foreach( $list['sqlString'] ?? [] as $sql ) {
			$event->addSqlData( $sql );
		}
	}


	/**
	 * Update schema if extension is installed
	 *
	 * @param AfterPackageActivationEvent $event Event object
	 */
	public function setupEvent( AfterPackageActivationEvent $event )
	{
		if( $event->getPackageKey() === 'aimeos' && \Aimeos\Aimeos\Base::getExtConfig( 'autoSetup', true ) ) {
			self::execute();
		}
	}


	/**
	 * Returns a new context object.
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	protected static function context() : \Aimeos\MShop\Context\Item\Iface
	{
		$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );

		if( file_exists( $aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
			require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
		}

		$ctx = new \Aimeos\MShop\Context\Item\Typo3();

		$conf = \Aimeos\Aimeos\Base::config();
		$ctx->setConfig( $conf );

		$ctx->setDatabaseManager( new \Aimeos\MW\DB\Manager\DBAL( $conf ) );
		$ctx->setLogger( new \Aimeos\MW\Logger\Errorlog( \Aimeos\MW\Logger\Base::INFO ) );
		$ctx->setSession( new \Aimeos\MW\Session\None() );
		$ctx->setCache( new \Aimeos\MW\Cache\None() );

		// Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
		if( php_sapi_name() === 'cli' && class_exists( '\TYPO3\CMS\Core\Database\ConnectionPool' )
			&& method_exists( '\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections' ) )
		{
			$ctx->setProcess( new \Aimeos\MW\Process\Pcntl( \Aimeos\Aimeos\Base::getExtConfig( 'pcntlMax', 4 ) ) );
		} else {
			$ctx->setProcess( new \Aimeos\MW\Process\None() );
		}

		$factory = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory' );
		return $ctx->setPassword( new \Aimeos\MW\Password\Typo3( $factory->getDefaultHashInstance( 'FE' ) ) );
	}
}
