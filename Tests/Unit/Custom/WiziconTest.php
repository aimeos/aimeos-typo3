<?php


class Tx_Aimeos_Tests_Unit_Custom_WiziconTest
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new tx_aimeos_custom_wizicon();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function proc()
	{
		$result = $this->_object->proc( array() );

		$this->assertArrayHasKey( 'plugins_tx_aimeos', $result );
		$this->assertArrayHasKey( 'icon', $result['plugins_tx_aimeos'] );
		$this->assertArrayHasKey( 'title', $result['plugins_tx_aimeos'] );
	}
}