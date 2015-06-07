<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class BasketControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->_object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\BasketController', array( 'dummy' ) );

		$objManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();

		$uriBuilder = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' );
		$response = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' );
		$request = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' );

		$uriBuilder->setRequest( $request );

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $request );
		}

		$this->_object->_set( 'uriBuilder', $uriBuilder );
		$this->_object->_set( 'response', $response );
		$this->_object->_set( 'request', $request );

		$this->_object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function indexAction()
	{
		$name = 'Client_Html_Basket_Standard_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Basket_Standard_Factory::injectClient( $name, $client );
		$output = $this->_object->indexAction();
		\Client_Html_Basket_Standard_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function smallAction()
	{
		$name = 'Client_Html_Basket_Mini_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Basket_Mini_Factory::injectClient( $name, $client );
		$output = $this->_object->smallAction();
		\Client_Html_Basket_Mini_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}