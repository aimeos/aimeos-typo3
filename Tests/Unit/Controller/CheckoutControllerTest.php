<?php


class Tx_Aimeos_Tests_Unit_Controller_CheckoutControllerTest
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = $this->getAccessibleMock( 'Tx_Aimeos_Controller_CheckoutController', array( 'dummy' ) );

		$objManager = new Tx_Extbase_Object_ObjectManager();

		$uriBuilder = $objManager->get( 'Tx_Extbase_MVC_Web_Routing_UriBuilder' );
		$response = $objManager->get( 'Tx_Extbase_MVC_Web_Response' );
		$request = $objManager->get( 'Tx_Extbase_MVC_Web_Request' );

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
		$name = 'Client_Html_Checkout_Standard_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->_object->indexAction();
		Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function indexActionException()
	{
		$name = 'Client_Html_Checkout_Standard_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Checkout_Standard_Factory::injectClient( $name, $client );
		$output = $this->_object->indexAction();
		Client_Html_Checkout_Standard_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
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

		Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->_object->confirmAction();
		Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function confirmActionException()
	{
		$name = 'Client_Html_Checkout_Confirm_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Checkout_Confirm_Factory::injectClient( $name, $client );
		$output = $this->_object->confirmAction();
		Client_Html_Checkout_Confirm_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
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

		Client_Html_Account_History_Factory::injectClient( $name, $client );
		$output = $this->_object->updateAction();
		Client_Html_Account_History_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function updateActionException()
	{
		$name = 'Client_Html_Checkout_Update_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Checkout_Update_Factory::injectClient( $name, $client );
		$output = $this->_object->updateAction();
		Client_Html_Checkout_Update_Factory::injectClient( $name, null );

		$this->assertStringStartsWith( 'Error', $output );
	}
}