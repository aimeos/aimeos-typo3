<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class CatalogControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->_object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\CatalogController', array( 'dummy' ) );

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
	public function countAction()
	{
		$name = 'Client_Html_Catalog_Count_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Catalog_Count_Factory::injectClient( $name, $client );
		$output = $this->_object->countAction();
		\Client_Html_Catalog_Count_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_Detail_Factory::injectClient( $name, $client );
		$output = $this->_object->detailAction();
		\Client_Html_Catalog_Detail_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_Filter_Factory::injectClient( $name, $client );
		$output = $this->_object->filterAction();
		\Client_Html_Catalog_Filter_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->listAction();
		\Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}


	/**
	 * @test
	 */
	public function suggestAction()
	{
		$name = 'Client_Html_Catalog_Suggest_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Catalog_List_Factory::injectClient( $name, $client );
		$output = $this->_object->suggestAction();
		\Client_Html_Catalog_List_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_Session_Factory::injectClient( $name, $client );
		$output = $this->_object->sessionAction();
		\Client_Html_Catalog_Session_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_Stage_Factory::injectClient( $name, $client );
		$output = $this->_object->stageAction();
		\Client_Html_Catalog_Stage_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
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

		\Client_Html_Catalog_Stock_Factory::injectClient( $name, $client );
		$output = $this->_object->stockAction();
		\Client_Html_Catalog_Stock_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}