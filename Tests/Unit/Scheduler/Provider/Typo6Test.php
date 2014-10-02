<?php


class Tx_Aimeos_Tests_Unit_Scheduler_Provider_Typo6Test
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		if( !interface_exists( 'TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface' ) ) {
			$this->markTestSkipped( 'Test is for TYPO3 6.x only' );
		}

		$this->_object = new Aimeos\Aimeos\Scheduler\Provider\Typo6();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function getAdditionalFields()
	{
		$taskInfo = array();
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();
		$module->CMD = 'edit';

		$result = $this->_object->getAdditionalFields( $taskInfo, $this->_object, $module );

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
		$manager = MShop_Attribute_Manager_Factory::createManager( Tx_Aimeos_Scheduler_Base::getContext() );

		$taskInfo = array();
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();
		$module->CMD = 'edit';

		MShop_Locale_Manager_Factory::injectManager( 'MShop_Locale_Manager_Default', $manager );
		$result = $this->_object->getAdditionalFields( $taskInfo, $this->_object, $module );
		MShop_Locale_Manager_Factory::injectManager( 'MShop_Locale_Manager_Default', null );

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
		$task = new Aimeos\Aimeos\Scheduler\Task\Typo6();

		$this->_object->saveAdditionalFields( $data, $task );

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
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoSite()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
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
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFields()
	{
		$data = array(
			'aimeos_sitecode' => 'default',
			'aimeos_controller' => 'catalog/index/optimize',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertTrue( $this->_object->validateAdditionalFields( $data, $module ) );
	}
}
