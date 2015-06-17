<?php


namespace Aimeos\Aimeos\Tests\Unit\Custom;


class WiziconTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new \Aimeos\Aimeos\Custom\Wizicon();
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function proc()
	{
		$result = $this->object->proc( array() );

		$this->assertArrayHasKey( 'plugins_tx_aimeos', $result );
		$this->assertArrayHasKey( 'icon', $result['plugins_tx_aimeos'] );
		$this->assertArrayHasKey( 'title', $result['plugins_tx_aimeos'] );
	}
}