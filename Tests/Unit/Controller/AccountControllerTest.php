<?php


namespace Aimeos\Aimeos\Tests\Unit\Controller;

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Extbase\Mvc\Request;

class AccountControllerTest
    extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    private $object;


    public function setUp()
    {
        \Aimeos\Aimeos\Base::aimeos(); // initialize autoloader

        $this->object = $this->getAccessibleMock('Aimeos\\Aimeos\\Controller\\AccountController', array('dummy'));

        $uriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
        $response = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Response::class);
        $request = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Request::class);

        $uriBuilder->setRequest($request);

        if (method_exists($response, 'setRequest')) {
            $response->setRequest($request);
        }

        $this->object->_set('uriBuilder', $uriBuilder);
        $this->object->_set('response', $response);
        $this->object->_set('request', $request);

        $this->object->_call('initializeAction');
    }


    public function tearDown()
    {
        unset($this->object);
    }


    /**
     * @test
     */
    public function downloadAction()
    {
        $name = '\\Aimeos\\Client\\Html\\Account\\Download\\Standard';
        $client = $this->getMock($name, array('process'), [], '', false);

        \Aimeos\Client\Html\Account\Download\Factory::injectClient($name, $client);
        $output = $this->object->downloadAction();
        \Aimeos\Client\Html\Account\Download\Factory::injectClient($name, null);

        $this->assertEquals('', $output);
    }


    /**
     * @test
     */
    public function historyAction()
    {
        $name = '\\Aimeos\\Client\\Html\\Account\\History\\Standard';
        $client = $this->getMock($name, array('getBody', 'getHeader', 'process'), [], '', false);

        $client->expects($this->once())->method('getBody')->will($this->returnValue('body'));
        $client->expects($this->once())->method('getHeader')->will($this->returnValue('header'));

        \Aimeos\Client\Html\Account\History\Factory::injectClient($name, $client);
        $output = $this->object->historyAction();
        \Aimeos\Client\Html\Account\History\Factory::injectClient($name, null);

        $this->assertEquals('body', $output);
    }
}