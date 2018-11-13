<?php


namespace Aimeos\Aimeos\Custom;

use TYPO3\CMS\Install\Updates\AbstractUpdate;


class UpdateWizard extends AbstractUpdate
{
    /**
     * @var string
     */
    protected $title = 'Aimeos database update and migration';


    /**
     * Checks whether updates are required.
     *
     * @param string $description The description for the update
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function checkForUpdate( &$description )
    {
        return true;
    }


   /**
    * Performs the required update.
    *
    * @param array $dbQueries Queries done in this update
    * @param string $customMessage Custom message to be displayed after the update process finished
    * @return bool Whether everything went smoothly or not
    */
    public function performUpdate( array &$databaseQueries, &$customMessage )
    {
		ob_start();
		$result = false;
		$exectimeStart = microtime( true );

		try
		{
			\Aimeos\Aimeos\Setup::execute();
			$output = ob_get_contents();
			$result = true;
		}
		catch( Exception $e )
		{
			$output = ob_get_contents();
			$output .= PHP_EOL . $e->getMessage();
			$output .= PHP_EOL . $e->getTraceAsString();
		}

		ob_end_clean();

		$customMessage = '<pre>' . $output . '</pre>' . PHP_EOL .
			sprintf( "Setup process lasted %1\$f sec</br>\n", (microtime( true ) - $exectimeStart) );

		return $result;
    }
}