<?php


namespace Aimeos\Aimeos\Tests\Unit\Flexform;


class CatalogTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$this->object = new \Aimeos\Aimeos\Flexform\Catalog();
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function getCategories()
	{
		$result = $this->object->getCategories( array( 'items' => array() ) );

		$this->assertArrayHasKey( 'items', $result );
	}
}