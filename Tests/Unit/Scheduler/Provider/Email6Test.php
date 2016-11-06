<?php


namespace Aimeos\Aimeos\Tests\Unit\Scheduler\Provider;


use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use Aimeos\Aimeos\Scheduler;


class Email6Test
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new Scheduler\Provider\Email6();
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
		$this->assertArrayHasKey( 'aimeos_sender_from', $result );
		$this->assertArrayHasKey( 'aimeos_sender_email', $result );
		$this->assertArrayHasKey( 'aimeos_reply_email', $result );
	}


	/**
	 * @test
	 */
	public function getAdditionalFieldsException()
	{
		$taskInfo = array();
		$module = new SchedulerModuleController();
		$module->CMD = 'edit';

		$mock = $this->getMockBuilder( '\Aimeos\Aimeos\Scheduler\Provider\Email6' )
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
			'aimeos_sender_from' => 'test name',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_reply_email' => 'reply@test',
		);
		$task = new Scheduler\Task\Typo6();

		$this->object->saveAdditionalFields( $data, $task );

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
			'aimeos_sender_email' => 'sender@test',
		);
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
	}


	/**
	 * @test
	 */
	public function validateAdditionalFieldsInvalidDownloadPage()
	{
		$data = array(
			'aimeos_controller' => 'testcntl',
			'aimeos_sitecode' => 'testsite',
			'aimeos_sender_email' => 'sender@test',
			'aimeos_pageid_detail' => '123',
			'aimeos_pageid_download' => 'a',
		);
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
		$module = new SchedulerModuleController();

		$this->assertFalse( $this->object->validateAdditionalFields( $data, $module ) );
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
			'aimeos_sender_email' => 'sender@test',
			'aimeos_pageid_detail' => '123',
			'aimeos_pageid_download' => '456',
			'aimeos_site_baseurl' => 'https://www.aimeos.org:80/',
			'aimeos_content_baseurl' => 'https://www.aimeos.org:80/up/tx_/',
			'aimeos_template_baseurl' => 'https://www.aimeos.org:80/fa/elegance',
		);
		$module = new SchedulerModuleController();

		$this->assertTrue( $this->object->validateAdditionalFields( $data, $module ) );
	}
}
