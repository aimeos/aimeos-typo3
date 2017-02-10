<?php
namespace Aimeos\Aimeos\Custom;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Romain CANON <romain.canon@exl-group.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\Core\Bootstrap;
use \TYPO3\CMS\Core\Utility\ArrayUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * Allows to easily dispatch an Ajax request.
 *
 * Two types of use can be done with it:
 *  -   It can be a call to a class function with some arguments (just like a
 *      user function). Please notice that your function should have two
 *      parameters:
 *          1. $content
 *          2. $configuration
 *  -   If you want to be in a controller context, you may also send some mvc
 *      arguments: plugin name, controller, action, etc.
 *
 * USAGE:
 *
 *  1 - Add this file in your extension at this path:
 *
 *      my_extension/Classes/Utility/AjaxDispatcherUtility.php
 *
 * ------------------------------
 *
 *  2 - Paste the following configuration in your ext_localconf.php. Notice that
 *      you can customize the name of the dispatcher, and whether the dispatcher
 *      should be called in a Frontend/Backend context.
 *      See function documentation for more information.
 *
 *      \MyVendor\MyExtension\Utility\AjaxDispatcherUtility::activateAjaxDispatcher();
 *
 * ------------------------------
 *
 *  3 - Call this class with Ajax. Example with jQuery below:
 *
 *      // Example of a simple class function call.
 *      var request = {
 *          // Attention: "id"=the id of the rootPage from which you want your
 *          // TypoScriptFrontendController ($GLOBALS['TSFE']) to be based on.
 *           id: 1,
 *          function: '\\MyVendor\\MyCustomClass\\MyFunction->foo',
 *          arguments: {
 *              foo: 'bar'
 *          }
 *      };
 *
 *      // Example of a controller call.
 *      var request = {
 *          // Attention: "id"=the id of the rootPage from which you want your
 *          // TypoScriptFrontendController ($GLOBALS['TSFE']) to be based on.
 *           id: 1,
 *           mvc: {
 *               vendor:            'MyVendor',
 *               extensionName:     'MyExtension',
 *               pluginName:        'MyPlugin',
 *               controller:        'MyController',
 *               action:            'foo'
 *           },
 *           arguments: {
 *               foo: 'bar'
 *           }
 *       };
 *
 *       jQuery.ajax({
 *           url:       'index.php', // If called in a Backend context, replace with "ajax.php".
 *           type:      'GET',
 *           dataType:  'html',
 *           data: {
 *               eID:       'ajaxDispatcher', // If called in a Backend context, "eID" must be replaced with "ajaxID".
 *               request:   request
 *           },
 *           success: function(result) {
 *              // Your actions here.
 *           }
 *       });
 */
class AjaxDispatcherUtility {
    /** @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
    private $objectManager;

    /**
     * Main function of the class, will run the function call process.
     *
     * See class documentation for more information.
     */
    public function run() {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        // Bootstrap initialization.
        Bootstrap::getInstance()
            ->initializeTypo3DbGlobal()
            //->applyAdditionalConfigurationSettings()
            ->initializeBackendUser();

        // Gets the Ajax call parameters.
        $arguments = GeneralUtility::_GP('request');

        // Initializing TypoScript Frontend Controller.
        if(TYPO3_MODE == 'FE') {
            // Creating global time tracker.
            $GLOBALS['TT'] = $this->objectManager->get('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker');

            $id = (isset($arguments['id'])) ? $arguments['id'] : 0;
            $this->initializeTSFE($id);
        }

        // If the argument "mvc" is sent, then we should be able to call a controller.
        if (isset($arguments['mvc'])) {
            $result = $this->callExtbaseController($arguments);
        }
        // Otherwise, it must be a user function call.
        else {
            $result = $this->callUserFunction($arguments);
        }

        // Display the final result on screen.
        echo $result;
    }

    /**
     * Run an Extbase call. See documentation for more information.
     *
     * @param   array   $arguments  Array containing the request arguments.
     * @return  string  The result of the Extbase request.
     */
    private function callExtbaseController($arguments) {
        $mvcArguments = array(
            'extensionName'                 => '',
            'pluginName'                    => '',
            'vendorName'                    => '',
            'controller'                    => '',
            'switchableControllerActions'   => ''
        );
        ArrayUtility::mergeRecursiveWithOverrule($mvcArguments, $arguments['mvc']);

        // If we're in a Backend context, the plugin name is a plugin signature. We need to find it.
        $pluginName = $mvcArguments['pluginName'];
        if (TYPO3_MODE == 'BE') {
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$mvcArguments['extensionName']]['modules'])) {
                foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$mvcArguments['extensionName']]['modules'] as $pluginKey => $pluginConfiguration) {
                    if (preg_match('#^[^_]+_' . $mvcArguments['extensionName'] . $mvcArguments['pluginName'] .'$#', $pluginKey)) {
                        $pluginName = $pluginKey;
                        break;
                    }
                }
            }
        }

        $bootstrapConfiguration = array(
            'extensionName'                 => $mvcArguments['extensionName'],
            'pluginName'                    => $pluginName,
            'vendorName'                    => $mvcArguments['vendor'],
            'controller'                    => $mvcArguments['controller'],
            'switchableControllerActions'   => array(
                $mvcArguments['controller']     => array($mvcArguments['action'])
            )
        );

		//throw new \RuntimeException( $mvcArguments['controller'] );
        // Add the controller arguments to the global $_GET var.
        /** @var $extensionService \TYPO3\CMS\Extbase\Service\ExtensionService */
        $extensionService = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\ExtensionService');
        $pluginNamespace = 'ai'; //$extensionService->getPluginNamespace($mvcArguments['extensionName'], $pluginName);
        GeneralUtility::_GETset($arguments['arguments'], $pluginNamespace);

        // Calling the controller by running an Extbase Bootstrap with the
        // correct configuration.
        /** @var $bootstrap \TYPO3\CMS\Extbase\Core\Bootstrap */
        $bootstrap = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Core\\Bootstrap');
        $result = $bootstrap->run('', $bootstrapConfiguration);

        return $result;
    }

    /**
     * Run a user function call. See documentation for more information.
     *
     * @param   array   $arguments  Array containing the request arguments.
     * @return  string  The result of the user function.
     */
    private function callUserFunction($arguments) {
        /** @var $contentObjectRenderer \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
        /** @var $userContentObject \TYPO3\CMS\Frontend\ContentObject\UserContentObject */
        $userContentObject = $contentObjectRenderer->getContentObject('USER');

        $configuration = (isset($arguments['arguments'])) ? $arguments['arguments'] : array();
        $configuration['userFunc'] = $arguments['function'];

        $result = $userContentObject->render($configuration);

        return $result;
    }

    /**
     * Initializes the $GLOBALS['TSFE'] var, useful everywhere when in a
     * Frontend context.
     *
     * @param int   $id     The if of the rootPage from which you want the controller to be based on.
     */
    private function initializeTSFE($id) {
        if(TYPO3_MODE == 'FE') {
            global $TYPO3_CONF_VARS;

            /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
            $GLOBALS['TSFE'] = $this->objectManager->get('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $TYPO3_CONF_VARS, $id, 0);
            EidUtility::initLanguage();
            EidUtility::initTCA();

            // No Cache for Ajax stuff.
            $GLOBALS['TSFE']->set_no_cache();

            $GLOBALS['TSFE']->initFEuser();
            $GLOBALS['TSFE']->checkAlternativeIdMethods();
            $GLOBALS['TSFE']->determineId();
            $GLOBALS['TSFE']->initTemplate();
            $GLOBALS['TSFE']->getPageAndRootline();
            $GLOBALS['TSFE']->getConfigArray();
            $GLOBALS['TSFE']->connectToDB();
            $GLOBALS['TSFE']->settingLanguage();
        }
    }

    /**
     * Activates the Ajax Dispatcher.
     *
     * Should be called from "ext_localconf.php".
     *
     * @param bool      $frontend   True if the dispatcher should be activated in a Frontend context.
     * @param bool      $backend    True if the dispatcher should be activated in a Backend context.
     * @param string    $name       The name of the dispatcher, used to access it in your JavaScript.
     */
    public static function activateAjaxDispatcher($frontend = true, $backend = true, $name = 'ajaxDispatcher') {
        if(TYPO3_MODE == 'BE' && $backend) {
            ExtensionManagementUtility::registerAjaxHandler($name, __CLASS__ . '->run');
        }
        if (TYPO3_MODE == 'FE' && $frontend) {
            $TYPO3_CONF_VARS['FE']['eID_include'][$name] = __FILE__;
        }
    }
}

/*  When called from a Frontend context, TYPO3 doesn't allow to define the
    ajaxDispatcher "run" function, so it needs to be called manually. */
if(TYPO3_MODE == 'FE' && GeneralUtility::_GET('eID')) {
    /** @var $ajaxDispatcher AjaxDispatcherUtility*/
    $ajaxDispatcher = new AjaxDispatcherUtility();
    $ajaxDispatcher->run();
}