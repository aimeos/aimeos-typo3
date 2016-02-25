<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class JqadmControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;
	private $request;
	private $view;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\Aimeos\Controller\JqadmController', array( 'dummy' ) );

		$this->request = $this->getMockBuilder( 'TYPO3\CMS\Extbase\Mvc\Web\Request' )
			->setMethods( array( 'hasArgument', 'getArgument' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->view = $this->getMockBuilder( 'TYPO3\CMS\Extbase\Mvc\View\EmptyView' )
			->setMethods( array( 'assign' ) )
			->disableOriginalConstructor()
			->getMock();

		$uriBuilder = $this->getMockBuilder( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' )
			->setMethods( array( 'buildBackendUri' ) )
			->disableOriginalConstructor()
			->getMock();


		$uriBuilder->expects( $this->any() )->method( 'buildBackendUri' );

		$this->object->_set( 'uriBuilder', $uriBuilder );
		$this->object->_set( 'request', $this->request );
		$this->object->_set( 'view', $this->view );

		$this->object->_call( 'initializeAction' );
	}


	public function tearDown()
	{
		unset( $this->object, $this->request, $this->view );
	}


	/**
	 * @test
	 */
	public function copyAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->copyAction();
	}


	/**
	 * @test
	 */
	public function createAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->createAction();
	}


	/**
	 * @test
	 */
	public function deleteAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->deleteAction();
	}


	/**
	 * @test
	 */
	public function getAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->getAction();
	}


	/**
	 * @test
	 */
	public function saveAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->saveAction();
	}


	/**
	 * @test
	 */
	public function searchAction()
	{
		$this->request->expects( $this->atLeastOnce() )->method( 'hasArgument' )
			->will( $this->returnValue( true ) );

		$this->request->expects( $this->any() )->method( 'getArgument' )
			->will( $this->onConsecutiveCalls( 'product', 'unittest', 'de' ) );

		$this->view->expects( $this->exactly( 2 ) )->method( 'assign' );

		$this->object->searchAction();
	}
}