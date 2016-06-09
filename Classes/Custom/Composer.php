<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;

use Composer\Script\CommandEvent;


/**
 * Performs setup and installation steps during composer installs
 *
 * @package TYPO3
 */
class Composer
{
	/**
	 * Sets up the shop database.
	 *
	 * @param CommandEvent $event CommandEvent instance
	 * @throws \RuntimeException If an error occured
	 */
	public static function setup( CommandEvent $event )
	{
		$event->getIO()->write( 'Setup the Aimeos database' );

		$vendorDir = $event->getComposer()->getConfig()->get( 'vendor-dir' );
		require_once $vendorDir . '/autoload.php';

		\Aimeos\Aimeos\Setup::execute();
	}


	/**
	 * Installs the shop files.
	 *
	 * @param CommandEvent $event CommandEvent instance
	 * @throws \RuntimeException If an error occured
	 */
	public static function install( CommandEvent $event )
	{
		$event->getIO()->write( 'Installing the Aimeos public files' );

		$t3package = $event->getComposer()->getRepositoryManager()->findPackage( 'aimeos/aimeos-typo3' );

		if( $t3package !== null )
		{
			$t3path = $event->getComposer()->getInstallerManager()->getInstallPath( $t3package );
			$package = $event->getComposer()->getRepositoryManager()->findPackage( 'aimeos/ai-client-html' );

			if( $package !== null )
			{
				$path = $event->getComposer()->getInstallerManager()->getInstallPath( $package );
				self::copyRecursive( $path . '/client/html/themes', $t3path . '/Resources/Public/Themes' );
			}

			$package = $event->getComposer()->getRepositoryManager()->findPackage( 'aimeos/ai-admin-extadm' );

			if( $package !== null )
			{
				$path = $event->getComposer()->getInstallerManager()->getInstallPath( $package );
				self::copyRecursive( $path . '/admin/extjs/resources', $t3path . '/Resources/Public/Admin/extjs/resources' );
			}
		}
	}


	/**
	 * Copies the source directory recursively to the destination
	 *
	 * @param string $src Source directory path
	 * @param string $dest Target directory path
	 * @throws \RuntimeException If an error occured
	 */
	protected static function copyRecursive( $src, $dest )
	{
		self::createDirectory( $dest );

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $src, \RecursiveDirectoryIterator::SKIP_DOTS ),
			\RecursiveIteratorIterator::SELF_FIRST
		);

		foreach( $iterator as $item )
		{
			$target = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

			if( $item->isDir() === false )
			{
				if( copy( $item, $target ) === false ) {
					throw new \RuntimeException( sprintf( 'Unable to copy file "%1$s"', $item ) );
				}
			}
			else
			{
				self::createDirectory( $target );
			}
		}
	}


	/**
	 * Creates a new directory if it doesn't exist yet
	 *
	 * @param string $dir Absolute path of the new directory
	 * @throws \RuntimeException If directory couldn't be created
	 */
	protected static function createDirectory( $dir )
	{
		$perm = 0755;

		if( is_dir( $dir ) === false && mkdir( $dir, $perm, true ) === false )
		{
			$msg = 'Unable to create directory "%1$s" with permission "%2$s"';
			throw new \RuntimeException( sprintf( $msg, $dir, $perm ) );
		}
	}


	/**
	 * Returns the available options defined in the composer file.
	 *
	 * @param CommandEvent $event Command event object
	 * @return array Associative list of option keys and values
	 */
	protected static function getOptions( CommandEvent $event )
	{
		return $event->getComposer()->getPackage()->getExtra();
	}
}
