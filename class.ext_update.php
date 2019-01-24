<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimos.org), 2014
 * @package TYPO3_Aimeos
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
	 * Array with possible flash messages.
	 *
	 * @var string
	 */
	protected $message = '';

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

		if ( $this->checkEnvironment() === false ) {
			return $this->message;
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
	 * TYPO3 9.5 and MySql 5.6 and below may make problems. This was introduced
	 * with #80398.
	 *
	 * @return bool
	 */
	protected function checkEnvironment(): bool
	{
		if (version_compare(TYPO3_version, '9.5', '<')) {
			// Wrong TYPO3 version.
			return true;
		}

		/** @var ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		/** @var ConnectionPool $connectionPool */
		$connectionPool = $objectManager->get(ConnectionPool::class);
		// A join with tables from different databases will throw an exception.
		// Hence, it is extreme likely that aimeos will be installed on the same
		// database.
		$connection = $connectionPool
			->getQueryBuilderForTable('fe_user')
			->getConnection();

		// Test the server type
		// @todo test MariaDB
		//	   According to the releases, the next version here would be 10.0
		if ( strpos( $connection->getServerVersion(), 'MySQL' ) === 0 &&
			version_compare( $connection->getWrappedConnection()->getServerVersion(), '5.7', '>=' )
		) {
			return true;
		}

		// Test the parameters
		$params = $connection->getParams();
		if ($params['tableoptions']['charset'] === 'utf8' ||
			$params['tableoptions']['collate'] === 'utf8_bin'
		) {
			return true;
		}

		// Retrieve the name of the connection (which is not part of the
		// connection class)
		foreach ( $connectionPool->getConnectionNames() as $name ) {
			if ($connectionPool->getConnectionByName($name) === $connection) {
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

		$this->message = $objectManager->get( FlashMessageRendererResolver::class )
			->resolve()
			->render( $flashMessages );

		return false;
	}
}
