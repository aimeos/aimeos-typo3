<?php


namespace Aimeos\Aimeos\Tests\Unit\Custom;


class RealurlTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new \Aimeos\Aimeos\Custom\Realurl();
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
		$obj = new \stdClass();
		$result = $this->_object->addAutoConfig( array(), $obj );

		$this->assertArrayHasKey( 'postVarSets', $result );
	}
}