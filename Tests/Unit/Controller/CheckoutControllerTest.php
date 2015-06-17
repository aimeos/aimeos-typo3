<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class CheckoutControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\CheckoutController', array( 'dummy' ) );

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
	public function indexAction()
	{
		$name = 'Client_Html_Checkout_Standard_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->object->indexAction();
		\Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function confirmAction()
	{
		$name = 'Client_Html_Checkout_Confirm_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->object->confirmAction();
		\Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function updateAction()
	{
		$name = 'Client_Html_Checkout_Update_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->object->updateAction();
		\Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}