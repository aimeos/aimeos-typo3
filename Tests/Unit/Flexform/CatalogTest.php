<?php


namespace Aimeos\AimeosShop\Tests\Unit\Flexform;


class CatalogTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = new \Aimeos\AimeosShop\Flexform\Catalog();
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function getCategories()
	{
		$result = $this->_object->getCategories( array( 'items' => array() ) );

		$this->assertArrayHasKey( 'items', $result );
	}
}