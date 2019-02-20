<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;

use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos context class
 *
 * @package TYPO3
 */
class Context
{
	private static $context;


	/**
	 * Returns the current context
	 *
	 * @param \Aimeos\MW\Config\Iface Configuration object
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function get( \Aimeos\MW\Config\Iface $config )
	{
		if( self::$context === null )
		{
			// TYPO3 specifc context with password hasher
			$context = new \Aimeos\MShop\Context\Item\Typo3();
			$context->setConfig( $config );

			self::addDataBaseManager( $context );
			self::addFilesystemManager( $context );
			self::addMessageQueueManager( $context );
			self::addLogger( $context );
			self::addCache( $context );
			self::addMailer( $context);
			self::addProcess( $context );
			self::addSession( $context );
			self::addHasher( $context);
			self::addUser( $context);
			self::addGroups( $context);

			self::$context = $context;
		}

		// Use local TS configuration from plugins
		self::$context->setConfig( $config );

		return self::$context;
	}


	/**
	 * Adds the cache object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object including config
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addCache( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_cache'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_cache'] ) )
		) {
			return $fcn( $context );
		}

		switch( \Aimeos\Aimeos\Base::getExtConfig( 'cacheName', 'Typo3' ) )
		{
			case 'None':
				$context->getConfig()->set( 'client/html/basket/cache/enable', false );
				$cache = \Aimeos\MW\Cache\Factory::createManager( 'None', array(), null );
				break;

			case 'Typo3':
				$manager = GeneralUtility::makeInstance( \TYPO3\CMS\Core\Cache\CacheManager::class );
				$cache = new \Aimeos\MAdmin\Cache\Proxy\Typo3( $context, $manager->getCache( 'aimeos' ) );
				break;

			default:
				$cache = new \Aimeos\MAdmin\Cache\Proxy\Standard( $context );
		}

		return $context->setCache( $cache );
	}


	/**
	 * Adds the database manager object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addDatabaseManager( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_dbm'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_dbm'] ) )
		) {
			return $fcn( $context );
		}

		return $context->setDatabaseManager( new \Aimeos\MW\DB\Manager\DBAL( $context->getConfig() ) );
	}


	/**
	 * Adds the filesystem manager object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addFilesystemManager( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_fsm'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_fsm'] ) )
		) {
			return $fcn( $context );
		}

		return $context->setFilesystemManager( new \Aimeos\MW\Filesystem\Manager\Standard( $context->getConfig() ) );
	}


	/**
	 * Adds the password hasher object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addHasher( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_hasher'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_hasher'] ) )
		) {
			return $fcn( $context );
		}

		if( class_exists( '\TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory' ) ) // TYPO3 9+
		{
			$factory = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory' );
			$context->setHasherTypo3( $factory->getDefaultHashInstance( 'FE' ) );
		}
		elseif( class_exists( '\TYPO3\CMS\Saltedpasswords\Salt\SaltFactory' ) ) // TYPO3 7/8
		{
			$context->setHasherTypo3( \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance() );
		}

		return $context;
	}


	/**
	 * Adds the logger object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addLogger( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_logger'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_logger'] ) )
		) {
			return $fcn( $context );
		}

		return $context->setLogger( \Aimeos\MAdmin\Log\Manager\Factory::createManager( $context ) );
	}


	/**
	 * Adds the mailer object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addMailer( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mailer'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mailer'] ) )
		) {
			return $fcn( $context );
		}

		return $context->setMail( new \Aimeos\MW\Mail\Typo3( function() {
			return GeneralUtility::makeInstance( \TYPO3\CMS\Core\Mail\MailMessage::class );
		} ) );
	}


	/**
	 * Adds the message queue manager object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addMessageQueueManager( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mqueue'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mqueue'] ) )
		) {
			return $fcn( $context );
		}

		return $context->setMessageQueueManager( new \Aimeos\MW\MQueue\Manager\Standard( $context->getConfig() ) );
	}


	/**
	 * Adds the process object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addProcess( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_process'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_process'] ) )
		) {
			return $fcn( $context );
		}

		// Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
		if( php_sapi_name() === 'cli'
			&& version_compare( TYPO3_version, '9.4', '>=' )
			&& method_exists( '\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections' ) )
		{
			$process = new \Aimeos\MW\Process\Pcntl( \Aimeos\Aimeos\Base::getExtConfig( 'pcntlMax', 4 ) );
		} else {
			$process = new \Aimeos\MW\Process\None();
		}

		return $context->setProcess( $process );
	}


	/**
	 * Adds the session object to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addSession( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_session'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_session'] ) )
		) {
			return $fcn( $context );
		}

		if( isset( $GLOBALS['TSFE']->fe_user ) ) {
			$session = new \Aimeos\MW\Session\Typo3( $GLOBALS['TSFE']->fe_user );
		} elseif( isset( $GLOBALS['BE_USER'] ) ) {
			$session = new \Aimeos\MW\Session\Typo3( $GLOBALS['BE_USER'] );
		} else {
			$session = new \Aimeos\MW\Session\None();
		}

		return $context->setSession( $session );
	}


	/**
	 * Adds the user ID and editor name to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addUser( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_user'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_user'] ) )
		) {
			return $fcn( $context );
		}

		if( TYPO3_MODE === 'FE' && $GLOBALS['TSFE']->loginUser == 1 )
		{
			$context->setUserId( $GLOBALS['TSFE']->fe_user->user[$GLOBALS['TSFE']->fe_user->userid_column] );
			$context->setEditor( $GLOBALS['TSFE']->fe_user->user['username'] );
		}
		elseif( TYPO3_MODE === 'BE' && isset( $GLOBALS['BE_USER']->user['username'] ) )
		{
			$context->setEditor( $GLOBALS['BE_USER']->user['username'] );
		}
		else
		{
			$context->setEditor( GeneralUtility::getIndpEnv( 'REMOTE_ADDR' ) );
		}

		return $context;
	}


	/**
	 * Adds the group IDs to the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function addGroups( \Aimeos\MShop\Context\Item\Iface $context )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_groups'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_groups'] ) )
		) {
			return $fcn( $context );
		}

		if( TYPO3_MODE === 'FE' && $GLOBALS['TSFE']->loginUser == 1 )
		{
			$ids = GeneralUtility::trimExplode( ',', $GLOBALS['TSFE']->fe_user->user['usergroup'] );
			$context->setGroupIds( $ids );
		}
		elseif( TYPO3_MODE === 'BE' )
		{
			$ids = array_keys( $GLOBALS['BE_USER']->userGroups );
			$context->setGroupIds( $ids );
		}

		return $context;
	}
}
