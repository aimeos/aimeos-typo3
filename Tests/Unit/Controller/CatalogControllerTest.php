<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class CatalogControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\CatalogController', array( 'dummy' ) );

		$objManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();

		$uriBuilder = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' );
		$response = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' );
		$request = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' );

		$uriBuilder->setRequest( $request );

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $request );
		}

		$this->object->_set( 'uriBuilder', $uriBuilder );
		$this->object->_set( 'response', $response );
		$this->object->_set( 'request', $request );

		$this->object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function countAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Count\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Count\Factory::injectClient( $name, $client );
		$output = $this->object->countAction();
		\Aimeos\Client\Html\Catalog\Count\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function detailAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Detail\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Detail\Factory::injectClient( $name, $client );
		$output = $this->object->detailAction();
		\Aimeos\Client\Html\Catalog\Detail\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function filterAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Filter\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Filter\Factory::injectClient( $name, $client );
		$output = $this->object->filterAction();
		\Aimeos\Client\Html\Catalog\Filter\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function listAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Lists\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Lists\Factory::injectClient( $name, $client );
		$output = $this->object->listAction();
		\Aimeos\Client\Html\Catalog\Lists\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function suggestAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Suggest\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Suggest\Factory::injectClient( $name, $client );
		$output = $this->object->suggestAction();
		\Aimeos\Client\Html\Catalog\Suggest\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function sessionAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Session\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Session\Factory::injectClient( $name, $client );
		$output = $this->object->sessionAction();
		\Aimeos\Client\Html\Catalog\Session\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function stageAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Stage\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Stage\Factory::injectClient( $name, $client );
		$output = $this->object->stageAction();
		\Aimeos\Client\Html\Catalog\Stage\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function stockAction()
	{
		$name = '\\Aimeos\\Client\\Html\\Catalog\\Stock\\Standard';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Aimeos\Client\Html\Catalog\Stock\Factory::injectClient( $name, $client );
		$output = $this->object->stockAction();
		\Aimeos\Client\Html\Catalog\Stock\Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}