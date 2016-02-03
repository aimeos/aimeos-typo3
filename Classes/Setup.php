<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


require_once dirname( __DIR__ ) . '/Resources/Libraries/autoload.php';


/**
 * Aimeos setup class.
 *
 * @package TYPO3
 */
class Setup
{
	/**
	 * Autoloader for setup tasks.
	 *
	 * @param string $classname Name of the class to load
	 * @return boolean True if class was found, false if not
	 */
	public static function autoload( $classname )
	{
		if( strncmp( $classname, 'Aimeos\\MW\\Setup\\Task\\', 21 ) === 0 )
		{
		    $fileName = substr( $classname, 21 ) . '.php';
			$paths = explode( PATH_SEPARATOR, get_include_path() );

			foreach( $paths as $path )
			{
				$file = $path . '/' . $fileName;

				if( file_exists( $file ) === true && ( include_once $file ) !== false ) {
					return true;
				}
			}
		}

		return false;
	}


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
		$taskPaths = $aimeos->getSetupPaths( 'default' );

		$includePaths = $taskPaths;
		$includePaths[] = get_include_path();

		if( set_include_path( implode( PATH_SEPARATOR, $includePaths ) ) === false ) {
			throw new \Exception( 'Unable to extend include path' );
		}

		if( spl_autoload_register( 'Aimeos\\Aimeos\\Setup::autoload' ) === false ) {
			throw new \Exception( 'Unable to register Aimeos\\Aimeos\\Setup::autoload' );
		}


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
				$config->set( "resource/$rname/limit", 2 );
			}
		}


		$class = 'TYPO3\CMS\Extbase\Object\ObjectManager';
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $class );
		$utility = $objectManager->get( 'TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility' );

		$conf = $utility->convertValuedToNestedConfiguration( $utility->getCurrentConfiguration( 'aimeos' ) );
		$value = ( isset( $conf['useDemoData'] ) ? $conf['useDemoData'] : '' );


		$local = array( 'setup' => array( 'default' => array( 'demo' => (string) $value ) ) );
		$ctx->setConfig( new \Aimeos\MW\Config\Decorator\Memory( $config, $local ) );

		$manager = new \Aimeos\MW\Setup\Manager\Multiple( $dbm, $dbconfig, $taskPaths, $ctx );
		$manager->run( 'mysql' );


		$conf['useDemoData'] = '';
		$utility->writeConfiguration( $conf, 'aimeos' );
	}


	/**
	 * Executes the setup tasks if extension is installed.
	 *
	 * @param string|null $extname Installed extension name
	 */
	public function executeOnSignal( $extname = null )
	{
		if( $extname === 'aimeos' ) {
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
		$ctx = new \Aimeos\MShop\Context\Item\Standard();

		$conf = \Aimeos\Aimeos\Base::getConfig();
		$ctx->setConfig( $conf );

		$dbm = new \Aimeos\MW\DB\Manager\PDO( $conf );
		$ctx->setDatabaseManager( $dbm );

		$logger = new \Aimeos\MW\Logger\Errorlog( \Aimeos\MW\Logger\Base::INFO );
		$ctx->setLogger( $logger );

		$session = new \Aimeos\MW\Session\None();
		$ctx->setSession( $session );

		$cache = new \Aimeos\MW\Cache\None();
		$ctx->setCache( $cache );

		return $ctx;
	}
}
