<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Aimeos context class
 *
 * @package TYPO3
 */
class Context
{
	private static $context;


	/**
	 * Returns the current context.
	 *
	 * @param \Aimeos\MW\Config\Iface Configuration object
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public function get( \Aimeos\MW\Config\Iface $config )
	{
		if( self::$context === null )
		{
			$context = new \Aimeos\MShop\Context\Item\Typo3();
			$context->setConfig( $config );

			$dbm = new \Aimeos\MW\DB\Manager\PDO( $config );
			$context->setDatabaseManager( $dbm );

			$fsm = new \Aimeos\MW\Filesystem\Manager\Standard( $config );
			$context->setFilesystemManager( $fsm );

			$mq = new \Aimeos\MW\MQueue\Manager\Standard( $config );
			$context->setMessageQueueManager( $mq );

			$logger = \Aimeos\MAdmin\Log\Manager\Factory::createManager( $context );
			$context->setLogger( $logger );

			$cache = $this->getCache( $context );
			$context->setCache( $cache );

			$context->setMail( new \Aimeos\MW\Mail\Typo3( function() {
				return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Mail\MailMessage' );
			} ) );

			if( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded( 'saltedpasswords' )
				&& \TYPO3\CMS\Saltedpasswords\Utility\SaltedPasswordsUtility::isUsageEnabled( 'FE' )
			) {
				$object = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance();
				$context->setHasherTypo3( $object );
			}

			if( isset( $GLOBALS['TSFE']->fe_user ) ) {
				$session = new \Aimeos\MW\Session\Typo3( $GLOBALS['TSFE']->fe_user );
			} else {
				$session = new \Aimeos\MW\Session\None();
			}
			$context->setSession( $session );

			self::$context = $context;
		}

		self::$context->setConfig( $config );

		return self::$context;
	}


	/**
	 * Returns the cache object for the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object including config
	 * @param string $siteid Unique site ID
	 * @return \Aimeos\MW\Cache\Iface Cache object
	 */
	protected function getCache( \Aimeos\MShop\Context\Item\Iface $context )
	{
		$config = $context->getConfig();

		switch( \Aimeos\Aimeos\Base::getExtConfig( 'cacheName', 'Typo3' ) )
		{
			case 'None':
				$config->set( 'client/html/basket/cache/enable', false );
				return \Aimeos\MW\Cache\Factory::createManager( 'None', array(), null );

			case 'Typo3':
				if( class_exists( '\TYPO3\CMS\Core\Cache\Cache' ) ) {
					\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
				}
				$manager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Cache\\CacheManager' );

				return new \Aimeos\MAdmin\Cache\Proxy\Typo3( $context, $manager->getCache( 'aimeos' ) );

			default:
				return new \Aimeos\MAdmin\Cache\Proxy\Standard( $context );
		}
	}
}
