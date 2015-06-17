<?php


namespace Aimeos\Aimeos\Tests\Unit\Command;


class JobsCommandControllerTest
	extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	private $object;


	public function setUp()
	{
		$name = 'TYPO3\CMS\Extbase\Object\ObjectManager';
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( $name );

		$this->object = new \Aimeos\Aimeos\Command\JobsCommandController();
		$this->object->injectObjectManager( $objectManager );
	}


	public function tearDown()
	{
		unset( $this->object );
	}


	/**
	 * @test
	 */
	public function runCommand()
	{
		$this->object->runCommand( 'catalog/index/optimize' );
	}


	/**
	 * @test
	 */
	public function emailCommand()
	{
		$this->object->emailCommand( 'catalog/index/optimize' );
	}
}
