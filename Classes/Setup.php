<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


use \TYPO3\CMS\Core\Utility\GeneralUtility;


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );

if( file_exists( $aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}


/**
 * Aimeos setup class.
 *
 * @package TYPO3
 */
class Setup
{
	/**
	 * Executes the setup tasks for updating the database.
	 *
	 * The setup tasks print their information directly to the standard output.
	 * To avoid this, it's necessary to use the output buffering handler
	 * (ob_start(), ob_get_contents() and ob_end_clean()).
	 */
	public static function execute()
	{
		ini_set( 'max_execution_time', 0 );

		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
		$sitecode = \Aimeos\Aimeos\Base::getExtConfig( 'siteCode', 'default' );
		$siteTpl = \Aimeos\Aimeos\Base::getExtConfig( 'siteTpl', 'default' );
		$taskPaths = $aimeos->getSetupPaths( $siteTpl );

		$ctx = self::getContext();
		$dbm = $ctx->getDatabaseManager();
		$config = $ctx->getConfig();

		$config->set( 'setup/site', $sitecode );

		$dbconfig = $config->get( 'resource', array() );

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

		$local = array( 'setup' => array( 'default' => array( 'demo' => (string) $demo ) ) );
		$ctx->setConfig( new \Aimeos\MW\Config\Decorator\Memory( $config, $local ) );

		$manager = new \Aimeos\MW\Setup\Manager\Multiple( $dbm, $dbconfig, $taskPaths, $ctx );
		$manager->migrate();

		$object->set( 'aimeos', 'useDemoData', '' );
	}


	/**
	 * Returns the current schema for the install tool
	 *
	 * @param array $sql List of SQL statements
	 * @return array SQL statements required for the install tool
	 */
	public function schema( array $sql ) : array
	{
		$ctx = self::getContext();
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
	 * Returns a new context object.
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	protected static function getContext() : \Aimeos\MShop\Context\Item\Iface
	{
		$ctx = new \Aimeos\MShop\Context\Item\Typo3();

		$conf = \Aimeos\Aimeos\Base::getConfig();
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
		return $ctx->setHasherTypo3( $factory->getDefaultHashInstance( 'FE' ) );
	}
}
