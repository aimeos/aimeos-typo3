<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimos.org), 2014-2019
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
	 * @return string|null A possible return message, stating what went wrong, if anything at all
	 */
	protected function checkEnvironment()
	{
		if( version_compare( TYPO3_version, '9.0', '<' ) ) {
			return;
		}

		$objectManager = GeneralUtility::makeInstance( ObjectManager::class );
		$connectionPool = $objectManager->get( TYPO3\CMS\Core\Database\ConnectionPool::class );
		$connection = $connectionPool->getQueryBuilderForTable( 'fe_user' )->getConnection();
		$params = $connection->getParams();

		if( strpos( $connection->getServerVersion(), 'MySQL' ) === false ) {
			return;
		}

		// MariaDB might get identified as a 'MySQL 5.5.5' for some reason
		$result = $connection->prepare('SELECT version()');
		$result->execute();
		$rows = $result->fetchAll();

		if( !isset( $rows[0]['version()'] ) ) {
			return;
		}

		$version = $rows[0]['version()']; // Something like '10.1.29-MariaDB' or '5.6.33-0ubuntu0'

		// Only MySQL < 5.7 and MariaDB < 10.2 don't work
		if( ( strpos( $version, 'MariaDB' ) !== false && version_compare( $version, '10.2', '>=' ) )
			|| version_compare( $version, '5.7', '>=' )
		) {
			return;
		}

		// MySQL < 5.7 and utf8mb4 charset doesn't work due to missing long index support
		if( isset( $params['tableoptions']['charset'] ) && $params['tableoptions']['charset'] !== 'utf8mb4' ) {
			return;
		}

		// Retrieve the name of the connection (which is not part of the connection class)
		foreach ( $connectionPool->getConnectionNames() as $name )
		{
			if( $connectionPool->getConnectionByName( $name ) === $connection ) {
				break;
			}
		}

		$msg = $objectManager->get(
			FlashMessage::class,
			LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_text', 'aimeos', [$name] ),
			LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_header' ),
			FlashMessage::ERROR
		);

		return $objectManager->get( FlashMessageRendererResolver::class )->resolve()->render( [$msg] ) .
			LocalizationUtility::translate( 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:t3_9x_config_error_remedy' );
	}
}
