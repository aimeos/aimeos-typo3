<?php


namespace Aimeos\Aimeos\Tests\Unit\Scheduler\Task;


class Email6Test
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new \Aimeos\Aimeos\Scheduler\Task\Email6();
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
