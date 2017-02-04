<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017-
 * @package TYPO3
 */

namespace Aimeos\Aimeos\EidDispatcher;


use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos eid dispatcher.
 *
 * @package TYPO3
 */
class Base {
	/**
	 * @var \array
	 */
	protected $configuration;

	/**
	 * @var \array
	 */
	protected $bootstrap;

	/**
	 * The main Method
	 *
	 * @return \string
	 */
	public function run() {
		return $this->bootstrap->run( '', $this->configuration );
	}

	/**
	 * Initialize Extbase
	 *
	 * @param \array $TYPO3_CONF_VARS
	 */
	public function __construct( $TYPO3_CONF_VARS ) {

	$ajaxRequest = GeneralUtility::_GET();

	// create bootstrap
	$this->bootstrap = new \TYPO3\CMS\Extbase\Core\Bootstrap();

	// get User
	$feUserObj = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();

	// set PID
	$pid = (GeneralUtility::_GET( 'id' )) ? GeneralUtility::_GET( 'id' ) : 1;

	// Create and init Frontend
	$GLOBALS['TSFE'] = GeneralUtility::makeInstance( 'TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $TYPO3_CONF_VARS, $pid, 0, TRUE );
	$GLOBALS['TSFE']->connectToDB();
	$GLOBALS['TSFE']->fe_user = $feUserObj;
	$GLOBALS['TSFE']->id = $pid;
	$GLOBALS['TSFE']->determineId();
	//$GLOBALS['TSFE']->getCompressedTCarray(); //Comment this line when used for TYPO3 7.6.0 on wards
	$GLOBALS['TSFE']->initTemplate();
	$GLOBALS['TSFE']->getConfigArray();
	//$GLOBALS['TSFE']->includeTCA(); //Comment this line when used for TYPO3 7.6.0 on wards


	// Get Plugins TypoScript
	$TypoScriptService = new \TYPO3\CMS\Extbase\Service\TypoScriptService();
	$pluginConfiguration = $TypoScriptService->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_aimeos.']);

	// Set configuration to call the plugin
	$this->configuration = array (
			'pluginName' => $ajaxRequest['plugin'],
			'vendorName' => 'Aimeos',
			'extensionName' => 'Aimeos',
			'controller' => $ajaxRequest['controller'],
			'action' => $ajaxRequest['action'],
			'mvc' => array (
					'requestHandlers' => array (
							'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' => 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler'
					)
			),
			'settings' => $pluginConfiguration['settings'],
			'persistence' => array (
					'storagePid' => $pluginConfiguration['persistence']['storagePid']
			)
		);

	}
}


global $TYPO3_CONF_VARS;

$TYPO3_CONF_VARS = $GLOBALS['TYPO3_CONF_VARS'];

// make instance of bootstrap and run
$eid = GeneralUtility::makeInstance( 'Aimeos\Aimeos\EidDispatcher\Base', $TYPO3_CONF_VARS );
echo $eid->run();

?>