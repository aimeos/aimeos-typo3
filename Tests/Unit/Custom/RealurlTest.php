<?php


class Tx_Aimeos_Tests_Unit_Custom_RealurlTest
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new tx_aimeos_custom_realurl();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function addAutoConfig()
	{
		$obj = new stdClass();
		$result = $this->_object->addAutoConfig( array(), $obj );

		$this->assertArrayHasKey( 'postVarSets', $result );
	}
}