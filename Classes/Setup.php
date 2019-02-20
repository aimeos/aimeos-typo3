<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


use \TYPO3\CMS\Core\Utility\GeneralUtility;


$localautoloader = dirname( __DIR__ ) . '/Resources/Libraries/autoload.php';

if( file_exists( $localautoloader ) === true ) {
	require_once $localautoloader;
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
	 *
	 * @param string|null $extname Installed extension name
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
				$config->set( "resource/$rname/limit", 3 );
			}
		}


		$class = \TYPO3\CMS\Extbase\Object\ObjectManager::class;
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $class );

		if( class_exists( '\TYPO3\CMS\Core\Configuration\ExtensionConfiguration' ) )
		{
			$object = $objectManager->get( 'TYPO3\CMS\Core\Configuration\ExtensionConfiguration' ); // TYPO3 9
			$demo = $object->get( 'aimeos', 'useDemoData' );
		}
		else
		{
			$object = $objectManager->get( 'TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility' ); // TYPO3 7+8

			$conf = $object->convertValuedToNestedConfiguration( $object->getCurrentConfiguration( 'aimeos' ) );
			$demo = ( isset( $conf['useDemoData'] ) ? $conf['useDemoData'] : '' );
		}


		$local = array( 'setup' => array( 'default' => array( 'demo' => (string) $demo ) ) );
		$ctx->setConfig( new \Aimeos\MW\Config\Decorator\Memory( $config, $local ) );

		$manager = new \Aimeos\MW\Setup\Manager\Multiple( $dbm, $dbconfig, $taskPaths, $ctx );
		$manager->migrate();

		if( \Aimeos\Aimeos\Base::getExtConfig( 'cleanDb', 1 ) == 1 ) {
			$manager->clean();
		}


		if( class_exists( '\TYPO3\CMS\Core\Configuration\ExtensionConfiguration' ) )
		{
			$object->set( 'aimeos', 'useDemoData', '' );
		}
		else
		{
			$conf['useDemoData'] = '';
			$object->writeConfiguration( $conf, 'aimeos' );
		}
	}


	/**
	 * Executes the setup tasks if extension is installed.
	 *
	 * @param string|null $extname Installed extension name
	 */
	public function schema( array $sql )
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

				while( ( $row = $result->fetch( \Aimeos\MW\DB\Result\Base::FETCH_NUM ) ) !== false ) {
					$tables[] = $row[0];
				}
			}

			foreach( $tables as $table )
			{
				$result = $conn->create( 'SHOW CREATE TABLE ' . $table )->execute();

				while( ( $row = $result->fetch( \Aimeos\MW\DB\Result\Base::FETCH_NUM ) ) !== false )
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


		return ['sqlString' => $sql ];
	}


	/**
	 * For existing installations
	 */
	public static function executeOnSignal( $extname = null )
	{
		self::signal( $extname );
	}


	/**
	 * Update schema if extension is installed
	 *
	 * @param string|null $extname Installed extension name
	 */
	public static function signal( $extname = null )
	{
		if( $extname === 'aimeos' && \Aimeos\Aimeos\Base::getExtConfig( 'autoSetup', true ) ) {
			self::execute();
		}
	}


	/**
	 * Returns a new context object.
	 *
	 * @return MShop_Context_Item_Interface Context object
	 */
	protected static function getContext()
	{
		$ctx = new \Aimeos\MShop\Context\Item\Typo3();

		$conf = \Aimeos\Aimeos\Base::getConfig();
		$ctx->setConfig( $conf );

		$ctx->setDatabaseManager( new \Aimeos\MW\DB\Manager\DBAL( $conf ) );
		$ctx->setLogger( new \Aimeos\MW\Logger\Errorlog( \Aimeos\MW\Logger\Base::INFO ) );
		$ctx->setSession( new \Aimeos\MW\Session\None() );
		$ctx->setCache( new \Aimeos\MW\Cache\None() );

		// Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
		if ( php_sapi_name() === 'cli' && class_exists( '\TYPO3\CMS\Core\Database\ConnectionPool' )
			&& method_exists( '\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections' ) )
		{
			$ctx->setProcess( new \Aimeos\MW\Process\Pcntl( \Aimeos\Aimeos\Base::getExtConfig( 'pcntlMax', 4 ) ) );
		} else {
			$ctx->setProcess( new \Aimeos\MW\Process\None() );
		}

		if( class_exists( '\TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory' ) ) // TYPO3 9+
		{
			$factory = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory' );
			$ctx->setHasherTypo3( $factory->getDefaultHashInstance( 'FE' ) );
		}
		elseif( class_exists( '\TYPO3\CMS\Saltedpasswords\Salt\SaltFactory' ) ) // TYPO3 7/8
		{
			$ctx->setHasherTypo3( \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance() );
		}

		return $ctx;
	}
}
