<?php


namespace Aimeos\Aimeos\Tests\Unit\Command;


class JobsCommandControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $_object;


	public function setUp()
	{
		$name = 'TYPO3\CMS\Extbase\Object\ObjectManager';
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $name );

		$this->_object = new \Aimeos\Aimeos\Command\JobsCommandController();
		$this->_object->injectObjectManager( $objectManager );
	}


	public function tearDown()
	{
		unset( $this->_object );
	}


	/**
	 * @test
	 */
	public function runCommand()
	{
		$this->_object->runCommand( 'catalog/index/optimize' );
	}


	/**
	 * @test
	 */
	public function emailCommand()
	{
		$this->_object->emailCommand( 'catalog/index/optimize' );
	}
}
