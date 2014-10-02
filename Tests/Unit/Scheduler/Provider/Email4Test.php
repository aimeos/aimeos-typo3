<?php


class tx_scheduler_EmailTask extends tx_scheduler_Task
{
	public function execute()
	{
	}
}


if( !class_exists( 'tx_scheduler_Module', false ) )
{
	class tx_scheduler_Module
	{
		public $CMD;

		public function addMessage( $message, $severity )
		{
		}
	}
}


class Tx_Aimeos_Tests_Unit_Scheduler_Provider_Email4Test
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		if( interface_exists( 'TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface' ) ) {
			$this->markTestSkipped( 'Test is for TYPO3 4.x only' );
		}

		$this->_object = new Tx_Aimeos_Scheduler_Provider_Email4();
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
		$module = new tx_scheduler_Module();
		$module->CMD = 'edit';

		$result = $this->_object->getAdditionalFields( $taskInfo, $this->_object, $module );

		$this->assertInternalType( 'array', $result );
		$this->assertArrayHasKey( 'aimeos_controller', $result );
		$this->assertArrayHasKey( 'aimeos_sitecode', $result );
		$this->assertArrayHasKey( 'aimeos_config', $result );
		$this->assertArrayHasKey( 'aimeos_sender_from', $result );
		$this->assertArrayHasKey( 'aimeos_sender_email', $result );
		$this->assertArrayHasKey( 'aimeos_pageid_detail', $result );
		$this->assertArrayHasKey( 'aimeos_content_baseurl', $result );
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
			'aimeos_pageid_detail' => '123',
			'aimeos_content_baseurl' => 'https://localhost/',
		);
		$task = new tx_scheduler_EmailTask();

		$this->_object->saveAdditionalFields( $data, $task );

		$this->assertEquals( 'testsite', $task->aimeos_sitecode );
		$this->assertEquals( 'testcntl', $task->aimeos_controller );
		$this->assertEquals( 'testconf', $task->aimeos_config );
		$this->assertEquals( 'test name', $task->aimeos_sender_from );
		$this->assertEquals( 'sender@test', $task->aimeos_sender_email );
		$this->assertEquals( 'reply@test', $task->aimeos_reply_email );
		$this->assertEquals( '123', $task->aimeos_pageid_detail );
		$this->assertEquals( 'https://localhost/', $task->aimeos_content_baseurl );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsNoController()
	{
		$data = array();
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

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
		$module = new tx_scheduler_Module();

		$this->assertFalse( $this->_object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFields()
	{
		$data = array(
			'aimeos_sitecode' => 'default',
			'aimeos_controller' => 'order/email/delivery',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_pageid_detail' => '123',
			'aimeos_content_baseurl' => 'https://www.aimeos.org:80/up/tx_/',
		);
		$module = new tx_scheduler_Module();

		$this->assertTrue( $this->_object->validateAdditionalFields( $data, $module ) );
	}
}
