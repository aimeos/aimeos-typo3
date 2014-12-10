<?php


namespace Aimeos\Aimeos\Tests\Unit;


require_once dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'class.ext_update.php';


class UpdateTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new \ext_update();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function access()
	{
		$this->assertTrue( $this->_object->access() );
	}


	/**
	 * @test
	 */
	public function main()
	{
		$result = $this->_object->main();

		$this->assertContains( 'Setup process lasted', $result );
	}
}