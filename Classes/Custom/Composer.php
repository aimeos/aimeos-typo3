<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;


/**
 * Performs setup and installation steps during composer installs
 *
 * @package TYPO3
 */
class Composer
{
	/**
	 * Installs the shop files.
	 *
	 * @param \Composer\Script\Event $event Event instance
	 * @throws \RuntimeException If an error occured
	 */
	public static function install( \Composer\Script\Event $event )
	{
		$repository = $event->getComposer()->getRepositoryManager();
		$t3package = $repository->findPackage( 'typo3-ter/aimeos', '*' );

		if( $t3package !== null )
		{
			$installer = $event->getComposer()->getInstallationManager();
			$t3path = $installer->getInstallPath( $t3package );

			if( ( $package = $repository->findPackage( 'aimeos/ai-client-html', '*' ) ) !== null )
			{
				$event->getIO()->write( 'Installing Aimeos public files from HTML client' );

				$path = $installer->getInstallPath( $package );
				self::copyRecursive( $path . '/client/html/themes', $t3path . '/Resources/Public/Themes' );
			}

			if( ( $package = $repository->findPackage( 'aimeos/ai-admin-extadm', '*' ) ) !== null )
			{
				$event->getIO()->write( 'Installing Aimeos public files from ExtJS admin' );

				$path = $installer->getInstallPath( $package );
				self::copyRecursive( $path . '/admin/extjs/resources', $t3path . '/Resources/Public/Admin/extjs/resources' );
				self::copyRecursive( $path . '/admin/extjs/lib/ext.ux/Portal/resources', $t3path . '/Resources/Public/Admin/extjs/lib/ext.ux/Portal' );
				self::copyRecursive( $path . '/admin/extjs/lib/ext.ux/AdvancedSearch/resources', $t3path . '/Resources/Public/Admin/extjs/lib/ext.ux/AdvancedSearch' );
			}

			if( ( $package = $repository->findPackage( 'aimeos/ai-typo3', '*' ) ) !== null )
			{
				$event->getIO()->write( 'Creating symlink to Aimeos extension directory' );

				if( file_exists( $t3path . '/Resources/Private/Extensions' ) === true ) {
					unlink( $t3path . '/Resources/Private/Extensions' );
				}

				$path = dirname( $installer->getInstallPath( $package ) );
				symlink( getcwd() . '/' . $path, $t3path . '/Resources/Private/Extensions' );
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
