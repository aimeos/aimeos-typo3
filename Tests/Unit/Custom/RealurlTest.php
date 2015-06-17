<?php


namespace Aimeos\Aimeos\Tests\Unit\Custom;


class RealurlTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new \Aimeos\Aimeos\Custom\Realurl();
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function addAutoConfig()
	{
		$obj = new \stdClass();
		$result = $this->object->addAutoConfig( array(), $obj );

		$this->assertArrayHasKey( 'postVarSets', $result );
	}
}