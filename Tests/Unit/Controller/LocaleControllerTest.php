<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;


class LocaleControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		\Aimeos\Aimeos\Base::getAimeos(); // initialize autoloader

		$this->object = $this->getAccessibleMock( 'Aimeos\\Aimeos\\Controller\\LocaleController', array( 'dummy' ) );

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
	public function selectAction()
	{
		$name = 'Client_Html_Locale_Select_Default';
		$client = $this->getMock( $name, array( 'getBody', 'getHeader', 'process' ), array(), '', false );

		$client->expects( $this->once() )->method( 'getBody' )->will( $this->returnValue( 'body' ) );
		$client->expects( $this->once() )->method( 'getHeader' )->will( $this->returnValue( 'header' ) );

		\Client_Html_Locale_Select_Factory::injectClient( $name, $client );
		$output = $this->object->selectAction();
		\Client_Html_Locale_Select_Factory::injectClient( $name, null );

		$this->assertEquals( 'body', $output );
	}
}