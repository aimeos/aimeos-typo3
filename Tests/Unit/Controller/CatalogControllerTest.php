<?php


class Tx_Aimeos_Tests_Unit_Controller_CatalogControllerTest
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = $this->getAccessibleMock( 'Tx_Aimeos_Controller_CatalogController', array( 'dummy' ) );

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
	public function countAction()
	{
		$name = 'Client_Html_Catalog_Count_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Count_Factory::injectClient( $name, $client );
		$output = $this->_object->countAction();
		Client_Html_Catalog_Count_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function countActionException()
	{
		$name = 'Client_Html_Catalog_Count_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Count_Factory::injectClient( $name, $client );
		$output = $this->_object->countAction();
		Client_Html_Catalog_Count_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function detailAction()
	{
		$name = 'Client_Html_Catalog_Detail_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Detail_Factory::injectClient( $name, $client );
		$output = $this->_object->detailAction();
		Client_Html_Catalog_Detail_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function detailActionException()
	{
		$name = 'Client_Html_Catalog_Detail_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Detail_Factory::injectClient( $name, $client );
		$output = $this->_object->detailAction();
		Client_Html_Catalog_Detail_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function filterAction()
	{
		$name = 'Client_Html_Catalog_Filter_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Filter_Factory::injectClient( $name, $client );
		$output = $this->_object->filterAction();
		Client_Html_Catalog_Filter_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function filterActionException()
	{
		$name = 'Client_Html_Catalog_Filter_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Filter_Factory::injectClient( $name, $client );
		$output = $this->_object->filterAction();
		Client_Html_Catalog_Filter_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function listAction()
	{
		$name = 'Client_Html_Catalog_List_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->listAction();
		Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function listActionException()
	{
		$name = 'Client_Html_Catalog_List_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->listAction();
		Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function listsimpleAction()
	{
		$name = 'Client_Html_Catalog_List_Simple';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->listsimpleAction();
		Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function listsimpleActionException()
	{
		$name = 'Client_Html_Catalog_List_Simple';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->listsimpleAction();
		Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function sessionAction()
	{
		$name = 'Client_Html_Catalog_Session_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Session_Factory::injectClient( $name, $client );
		$output = $this->_object->sessionAction();
		Client_Html_Catalog_Session_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function sessionActionException()
	{
		$name = 'Client_Html_Catalog_Stage_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Session_Factory::injectClient( $name, $client );
		$output = $this->_object->stageAction();
		Client_Html_Catalog_Session_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function stageAction()
	{
		$name = 'Client_Html_Catalog_Stage_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Stage_Factory::injectClient( $name, $client );
		$output = $this->_object->stageAction();
		Client_Html_Catalog_Stage_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function stageActionException()
	{
		$name = 'Client_Html_Catalog_Stage_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Stage_Factory::injectClient( $name, $client );
		$output = $this->_object->stageAction();
		Client_Html_Catalog_Stage_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}


	/**
	 * @test
	 */
	public function stockAction()
	{
		$name = 'Client_Html_Catalog_Stock_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		Client_Html_Catalog_Stock_Factory::injectClient( $name, $client );
		$output = $this->_object->stockAction();
		Client_Html_Catalog_Stock_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function stockActionException()
	{
		$name = 'Client_Html_Catalog_Stock_Default';
		$client = $this->getMock( $name, array( 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'process' )->will( $this->throwException( new Exception() ) );

		Client_Html_Catalog_Stock_Factory::injectClient( $name, $client );
		$output = $this->_object->stockAction();
		Client_Html_Catalog_Stock_Factory::injectClient( $name, null );

		$this->assertEquals( 1, count( t3lib_FlashMessageQueue::getAllMessagesAndFlush() ) );
		$this->assertNull( $output );
	}
}