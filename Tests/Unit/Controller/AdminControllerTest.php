<?php


class Tx_Aimeos_Tests_Unit_Controller_AdminControllerTest
	extends Tx_Extbase_Tests_Unit_BaseTestCase
{
	private $_object;


	public function setUp()
	{
		$this->_object = $this->getAccessibleMock( 'Tx_Aimeos_Controller_AdminController', array( 'dummy' ) );
		$this->_view = $this->getMock( 'Tx_Fluid_View_TemplateView', array(), array(), '', false );

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
		$this->_object->_set( 'view', $this->_view );

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
		$this->_view->expects( $this->atLeastOnce() )->method( 'assign' );

		$this->_object->indexAction();
	}


	/**
	 * @test
	 */
	public function doAction()
	{
		$this->_view->expects( $this->once() )->method( 'assign' )
			->with( $this->equalTo( 'response' ), $this->stringContains( '{' ) );

		$this->_object->doAction();
	}
}