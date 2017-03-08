<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class JsonapiControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;
	private $request;
	private $response;
	private $uriBuilder;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\JsonapiController', array( 'dummy' ) );

		$this->request = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Request' )
			->setMethods( array( 'hasArgument', 'getArgument', 'getMethod' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->response = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Response' )
			->setMethods( array( 'setStatus' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->uriBuilder = $this->getMockBuilder( 'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder' )
			->setMethods( array( 'buildFrontendUri' ) )
			->disableOriginalConstructor()
			->getMock();

		if( method_exists( $response, 'setRequest' ) ) {
			$response->setRequest( $this->request );
		}

		$this->object->_set( 'uriBuilder', $this->uriBuilder );
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
	public function optionsAction()
	{
		$this->request->expects( $this->exactly( 1 ) )->method( 'getMethod' )
		->will( $this->returnValue( 'OPTIONS' ) );

		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
		->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
		->will( $this->onConsecutiveCalls( '', 'unittest', null, null, 'unittest', null, null ) );

		$this->response->expects( $this->exactly( 1 ) )->method( 'setStatus' )
		->with( $this->equalTo( 200 ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
		$this->assertArrayHasKey( 'meta', $json );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );
		$this->assertArrayHasKey( 'product', $json['meta']['resources'] );
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
			->will( $this->onConsecutiveCalls( 'product', 'unittest', null, null, 'unittest', null, null ) );

		$this->response->expects( $this->exactly( 1 ) )->method( 'setStatus' )
			->with( $this->equalTo( 200 ) );

		$this->uriBuilder->expects( $this->any() )->method( 'buildFrontendUri' )
			->will( $this->returnValue( 'http://localhost/' ) );

		$result = $this->object->indexAction();
		$json = json_decode( $result, true );

		$this->assertNotEquals( null, $json );
	}

}