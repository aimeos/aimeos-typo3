<?php


namespace Aimeos\Aimeos\Tests\Unit\Scheduler\Provider;


use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;


class Typo6Test
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new \Aimeos\Aimeos\Scheduler\Provider\Typo6();
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function getAdditionalFields()
	{
		$taskInfo = array();
		$module = new SchedulerModuleController();
		$module->CMD = 'edit';

		$result = $this->object->getAdditionalFields( $taskInfo, $this->object, $module );

		$this->assertInternalType( 'array', $result );
		$this->assertArrayHasKey( 'aimeos_controller', $result );
		$this->assertArrayHasKey( 'aimeos_sitecode', $result );
		$this->assertArrayHasKey( 'aimeos_config', $result );
	}


	/**
	 * @test
	 */
	public function getAdditionalFieldsException()
	{
		$taskInfo = array();
		$module = new SchedulerModuleController();
		$module->CMD = 'edit';

		$mock = $this->getMockBuilder( '\Aimeos\Aimeos\Scheduler\Provider\Typo6' )
			->setMethods( array( 'getFields' ) )->getMock();

		$mock->expects( $this->once() )->method( 'getFields' )
			->will( $this->throwException( new \RuntimeException()  ) );

		$result = $mock->getAdditionalFields( $taskInfo, $mock, $module );

		$this->assertEquals( array(), $result );
	}


	/**
	 * @test
	 */
	public function saveAdditionalFields()
	{
		$data = array(
			'aimeos_sitecode' => 'testsite',
			'aimeos_controller' => 'testcntl',
			'aimeos_config' => 'testconf',
		);
		$task = new \Aimeos\Aimeos\Scheduler\Task\Typo6();

		$this->object->saveAdditionalFields( $data, $task );

		$this->assertEquals( 'testsite', $task->aimeos_sitecode );
		$this->assertEquals( 'testcntl', $task->aimeos_controller );
		$this->assertEquals( 'testconf', $task->aimeos_config );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoController()
	{
		$data = array();
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoSite()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
		);
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoSiteFound()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_config' => 'testconf',
		);
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFields()
	{
		$data = array(
			'aimeos_sitecode' => 'default',
			'aimeos_controller' => 'index/optimize',
		);
		$module = new SchedulerModuleController();

		$this->assertTrue( $this->object->validateAdditionalFields( $data, $module ) );
	}
}
