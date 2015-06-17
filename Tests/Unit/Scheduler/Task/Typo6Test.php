<?php


namespace Aimeos\Aimeos\Tests\Unit\Scheduler\Task;


class Typo6Test
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new \Aimeos\Aimeos\Scheduler\Task\Typo6();
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function execute()
	{
		$result = $this->object->execute();

		$this->assertTrue( $result );
	}
}
