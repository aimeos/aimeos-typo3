<?php


class Tx_Aimeos_Tests_Unit_Scheduler_Provider_Email6Test
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		if( !interface_exists( 'TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface' ) ) {
			$this->markTestSkipped( 'Test is for TYPO3 6.x only' );
		}

		$this->_object = new Aimeos\Aimeos\Scheduler\Provider\Email6();
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
		$this->assertArrayHasKey( 'aimeos_sender_from', $result );
		$this->assertArrayHasKey( 'aimeos_sender_email', $result );
		$this->assertArrayHasKey( 'aimeos_reply_email', $result );
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
			'aimeos_sender_from' => 'test name',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_reply_email' => 'reply@test',
		);
		$task = new Aimeos\Aimeos\Scheduler\Task\Typo6();

		$this->_object->saveAdditionalFields( $data, $task );

		$this->assertEquals( 'testsite', $task->aimeos_sitecode );
		$this->assertEquals( 'testcntl', $task->aimeos_controller );
		$this->assertEquals( 'testconf', $task->aimeos_config );
		$this->assertEquals( 'test name', $task->aimeos_sender_from );
		$this->assertEquals( 'sender@test', $task->aimeos_sender_email );
		$this->assertEquals( 'reply@test', $task->aimeos_reply_email );
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
			'aimeos_sender_email' => 'sender@test',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoSenderEmail()
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
	public function validateAdditionalFieldsInvalidSenderEmail()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_config' => 'testconf',
			'aimeos_sender_email' => 'sender-test',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsInvalidReplyEmail()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_config' => 'testconf',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_reply_email' => 'reply-test',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsInvalidPageID()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_pageid_detail' => 'a',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsInvalidBaseurlNoProtocol()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_content_baseurl' => 'localhost',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsInvalidBaseurlNoDomain()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_content_baseurl' => 'https:///',
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
			'aimeos_sender_email' => 'sender@test',
			'aimeos_pageid_detail' => '123',
			'aimeos_content_baseurl' => 'https://www.aimeos.org:80/up/tx_/',
		);
		$module = new \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController();

		$this->assertTrue( $this->_object->validateAdditionalFields( $data, $module ) );
	}
}
