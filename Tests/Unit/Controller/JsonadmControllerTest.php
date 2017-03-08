<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class JsonadmControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\JsonadmController', array( 'dummy' ) );

		$this->request = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' )
			->setMethods( array( 'hasArgument', 'getArgument', 'getMethod' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->response = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' )
			->setMethods( array( 'setStatus' ) )
			->disableOriginalConstructor()
			->getMock();

		$objManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		$uriBuilder = $objManager->get( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' );

		$uriBuilder->setRequest( $this->request );

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $this->request );
		}

		$this->object->_set( 'uriBuilder', $uriBuilder );
		$this->object->_set( 'response', $this->response );
		$this->object->_set( 'request', $this->request );

		$this->object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->object, $this->request, $this->response );
	}


	/**
	 * @test
	 */
	public function getAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'GET' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'stock/type', null, 'unittest', null, null, 'unittest', null ) );

		$this->response->expects( $this->exactly( 1 ) )->method( 'setStatus' )
			->with( $this->equalTo( 200 ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}


	/**
	 * @test
	 */
	public function optionsAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'OPTIONS' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( null, null, 'unittest', null, null, 'unittest', null ) );

		$this->response->expects( $this->exactly( 1 ) )->method( 'setStatus' )
			->with( $this->equalTo( 200 ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
		$this->assertArrayHasKey( 'meta', $json );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );
		$this->assertArrayHasKey( 'stock/type', $json['meta']['resources'] );
	}


	/**
	 * @test
	 */
	public function deleteAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'DELETE' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'stock/type', null, 'unittest', null, null, 'unittest', null ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}


	/**
	 * @test
	 */
	public function patchAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'PATCH' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'stock/type', null, 'unittest', null, null, 'unittest', null ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}


	/**
	 * @test
	 */
	public function postAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'POST' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'stock/type', null, 'unittest', null, null, 'unittest', null ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}


	/**
	 * @test
	 */
	public function putAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
			->will( $this->returnValue( 'PUT' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'stock/type', null, 'unittest', null, null, 'unittest', null ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}
}