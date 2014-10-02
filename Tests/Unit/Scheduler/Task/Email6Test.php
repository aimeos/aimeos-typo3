<?php


class Tx_Aimeos_Tests_Unit_Scheduler_Task_Email6Test
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		if( !class_exists( '\TYPO3\CMS\Scheduler\Task\AbstractTask' ) ) {
			$this->markTestSkipped( 'Test is for TYPO3 6.x only' );
		}

		$this->_object = new Aimeos\Aimeos\Scheduler\Task\Email6();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function execute()
	{
		$result = $this->_object->execute();

		$this->assertTrue( $result );
	}
}
