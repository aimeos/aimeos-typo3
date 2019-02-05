<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimos.org), 2014
 * @package TYPO3_Aimeos
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Messaging\FlashMessageRendererResolver;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

$localautoloader = __DIR__ . '/Resources/Libraries/autoload.php';

if( file_exists( $localautoloader ) === true ) {
	require_once $localautoloader;
}


/**
 * Update class for Aimeos TYPO3 extension.
 *
 * @package TYPO3_Aimeos
 */
class ext_update
{
	/**
	 * Returns the status if an update is necessary.
	 *
	 * @return boolean True if the update entry is available, false if not
	 */
	public function access()
	{
		return true;
	}

	/**
	 * Main update method called by the extension manager.
	 *
	 * @return string Messages
	 */
	public function main()
	{
		ob_start();
		$exectimeStart = microtime( true );

		if( ( $result = $this->checkEnvironment() ) !== null ) {
			return $result;
		}

		try
		{
			\Aimeos\Aimeos\Setup::execute();
			$output = ob_get_contents();
		}
		catch( Exception $e )
		{
			$output = ob_get_contents();
			$output .= PHP_EOL . $e->getMessage();
			$output .= PHP_EOL . $e->getTraceAsString();
		}

		ob_end_clean();


		return '<pre>' . $output . '</pre>' . PHP_EOL .
			sprintf( "Setup process lasted %1\$f sec</br>\n", (microtime( true ) - $exectimeStart) );
	}

	/**
	 * Check the environment.
	 *
	 * TYPO3 9.5 and MySql 5.6 or MariaDB 10.1 might make problems.
	 *
	 * @return null|string
	 *   A possible return message, stating what went wrong, if anything at all.
	 */
	protected function checkEnvironment()
	{
		if( version_compare( TYPO3_version, '9.0', '<' ) )
		{
			// Wrong TYPO3 version.
			return;
		}

		/** @var ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance( ObjectManager::class );

		/** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
		$connectionPool = $objectManager->get( TYPO3\CMS\Core\Database\ConnectionPool::class );
		// A join with tables from different databases will throw an exception.
		// Hence, it is extreme likely that aimeos will be installed on the same
		// database where the fe_user is located.
		$connection = $connectionPool
			->getQueryBuilderForTable( 'fe_user' )
			->getConnection();

		// Test the server type
		if( strpos( $connection->getServerVersion(), 'MySQL' ) === 0 )
		{
			// When dealing with a MariaDB, we can not rely on doctrine to tell the truth.
			// It might get identified as a 'MySql 5.5.5' for some reason.
			$result = $connection->prepare('SELECT version()');
			$result->execute();
			// Something like '10.1.29-MariaDB' or '5.6.33-0ubuntu0...'
			$version = $result->fetchAll()[0]['version()'];

			if( ( strpos( $version, 'MariaDB' ) !== false
				&& version_compare( $version, '10.2', '>=' ) )
				|| version_compare( $version, '5.7', '>=' )
			) {
				return;
			}
		}

		// Test the parameters
		// When the parameters are not set, the sql will fail.
		$params = $connection->getParams();
		if( isset( $params['tableoptions']['charset'] ) && $params['tableoptions']['charset'] === 'utf8' )
		{
			return;
		}

		// Retrieve the name of the connection (which is not part of the
		// connection class)
		foreach ( $connectionPool->getConnectionNames() as $name ) {
			if( $connectionPool->getConnectionByName( $name ) === $connection ) {
				break;
			}
		}

		// We have a winner!
		$flashMessages =[
			$objectManager->get(
				FlashMessage::class,
				LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_text', 'aimeos', [$name] ),
				LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_header' ),
				FlashMessage::ERROR
			)
		];

		return $objectManager->get( FlashMessageRendererResolver::class )
			->resolve()
			->render( $flashMessages ) .
			LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_remedy' );

	}
}
