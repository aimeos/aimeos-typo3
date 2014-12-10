<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Scheduler\Task;


/**
 * Aimeos scheduler.
 *
 * @package TYPO3_Aimeos
 */
class Typo6 extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
	private $_fieldSite = 'aimeos_sitecode';
	private $_fieldController = 'aimeos_controller';
	private $_fieldTSconfig = 'aimeos_config';


	/**
	 * Executes the configured tasks.
	 *
	 * @return boolean True if success, false if not
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->user['lang'] ) && $GLOBALS['BE_USER']->user['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->user['lang'];
		}

		$sitecodes = (array) $this->{$this->_fieldSite};
		$controllers = (array) $this->{$this->_fieldController};
		$tsconfig = $this->{$this->_fieldTSconfig};

		return \Aimeos\Aimeos\Scheduler\Base::execute( $sitecodes, $controllers, $tsconfig, $langid );
	}
}
