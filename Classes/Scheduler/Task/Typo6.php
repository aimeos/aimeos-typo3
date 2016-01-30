<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Scheduler\Task;

use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Aimeos scheduler.
 *
 * @package TYPO3
 */
class Typo6 extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
	private $fieldSite = 'aimeos_sitecode';
	private $fieldController = 'aimeos_controller';
	private $fieldTSconfig = 'aimeos_config';


	/**
	 * Executes the configured tasks.
	 *
	 * @return boolean True if success, false if not
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$sitecodes = (array) $this->{$this->fieldSite};
		$controllers = (array) $this->{$this->fieldController};
		$tsconfig = $this->{$this->fieldTSconfig};

		$conf = Base::parseTS( $tsconfig );
		Scheduler\Base::execute( $conf, $controllers, $sitecodes );

		return true;
	}
}
