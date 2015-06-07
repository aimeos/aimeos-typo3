<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class AdminControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->_object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\AdminController', array( 'dummy' ) );
		$this->_view = $this->getMock( 'TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', false );

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