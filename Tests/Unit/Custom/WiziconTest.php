<?php


namespace Aimeos\AimeosShop\Tests\Unit\Custom;


class WiziconTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new \Aimeos\AimeosShop\Custom\Wizicon();
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