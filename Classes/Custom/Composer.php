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
		$repository = $event->getComposer()->getRepositoryManager()->getLocalRepository();
		$t3package = $repository->findPackage( 'aimeos/aimeos-typo3', '*' );

		if ($t3package === null) {
			$t3package = $repository->findPackage( 'typo3-ter/aimeos-typo3', '*' );
		}

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

			if( ( $package = $repository->findPackage( 'aimeos/ai-typo3', '*' ) ) !== null )
			{
				$event->getIO()->write( 'Creating symlink to Aimeos extension directory' );

				$path = dirname( $installer->getInstallPath( $package ) );
				self::createLink( '../../../../../../' . $path, $t3path . '/Resources/Private/Extensions' );
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
	 * Creates a link if it doesn't exist
	 *
	 * @param string $source Relative source path
	 * @param string $target Absolute target path
	 */
	protected static function createLink( $source, $target )
	{
		if( file_exists( $target ) === false && symlink( $source, $target ) === false )
		{
			$source = realpath( __DIR__ . $source );

			if( symlink( $source, $target ) === false ) {
				throw new \RuntimeException( sprintf( 'Failed to create symlink for "%1$s" to "%2$s"', $source, $target ) );
			}
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
