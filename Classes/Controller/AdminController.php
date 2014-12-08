<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\AimeosShop\Controller;


use Aimeos\AimeosShop\Base;


/**
 * Controller for adminisration interface.
 *
 * @package TYPO3_Aimeos
 */
class AdminController extends AbstractController
{
	private $_controller;


	public function __construct()
	{
		parent::__construct();

		$cntlPaths = Base::getAimeos()->getCustomPaths( 'controller/extjs' );
		$this->_controller = new \Controller_ExtJS_JsonRpc( $this->_getContext(), $cntlPaths );
	}


	/**
	 * Sends the index file for the admin interface.
	 *
	 * @return Index file
	 */
	public function indexAction()
	{
		$html = '';
		$abslen = strlen( PATH_site );
		$langid = $this->_getContext()->getLocale()->getLanguageId();

		foreach( Base::getAimeos()->getCustomPaths( 'client/extjs' ) as $base => $paths )
		{
			$relJsbPath = '../' . substr( $base, $abslen );

			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;

				if( !is_file( $jsbAbsPath ) ) {
					throw new \Exception( sprintf( 'JSB2 file "%1$s" not found', $jsbAbsPath ) );
				}

				$jsb2 = new \MW_Jsb2_Default( $jsbAbsPath, $relJsbPath . '/' . dirname( $path ) );
				$html .= $jsb2->getHTML( 'css' );
				$html .= $jsb2->getHTML( 'js' );
			}
		}

		// rawurldecode() is necessary for ExtJS templates to replace "{site}" properly
		$urlTemplate = rawurldecode( \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeosshop_web_aimeosshoptxaimeosshopadmin' => array( 'site' => '{site}', 'tab' => '{tab}' ) ) ) );
		$serviceUrl = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeosshop_web_aimeosshoptxaimeosshopadmin' => array( 'controller' => 'Admin', 'action' => 'do' ) ) );

		$this->view->assign( 'htmlHeader', $html );
		$this->view->assign( 'lang', $langid );
		$this->view->assign( 'i18nContent', $this->_getJsonClientI18n( $langid ) );
		$this->view->assign( 'config', $this->_getJsonClientConfig() );
		$this->view->assign( 'site', $this->_getSite( $this->request ) );
		$this->view->assign( 'smd', $this->_controller->getJsonSmd( $serviceUrl ) );
		$this->view->assign( 'itemSchemas', $this->_controller->getJsonItemSchemas() );
		$this->view->assign( 'searchSchemas', $this->_controller->getJsonSearchSchemas() );
		$this->view->assign( 'activeTab', ( $this->request->hasArgument( 'tab' ) ? (int) $this->request->getArgument( 'tab' ) : 0 ) );
		$this->view->assign( 'urlTemplate', $urlTemplate );
	}


	/**
	 * Single entry point for all MShop admin requests.
	 *
	 * @return JSON 2.0 RPC message response
	 */
	public function doAction()
	{
		$this->view->assign( 'response', $this->_controller->process( $this->request->getArguments(), 'php://input' ) );
	}


	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->uc['lang'];
		}

		$context = $this->_getContext();
		
		$conf = $this->_getConfig( ( is_array( $this->settings ) ? $this->settings : array() ) );
		$context->setConfig( $conf );

		$localeManager = \MShop_Locale_Manager_Factory::createManager( $context );
		
		try {
			$sitecode = $conf->get( 'mshop/locale/site', 'default' );
			$localeItem = $localeManager->bootstrap( $sitecode, $langid, '', false );
		} catch( \MShop_Locale_Exception $e ) {
			$localeItem = $localeManager->createItem();
		}

		$localeItem->setLanguageId( $langid );
		$context->setLocale( $localeItem );

		$context->setCache( $this->_getCache( $conf, $localeItem->getSiteId() ) );
		$context->setI18n( $this->_getI18n( array( $langid ) ) );
		$context->setEditor( $GLOBALS['BE_USER']->user['username'] );
	}


	/**
	 * Uses default view.
	 *
	 * return Tx_Extbase_MVC_View_ViewInterface View object
	 */
	protected function resolveView()
	{
		return \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::resolveView();
	}


	/**
	 * Returns the JSON encoded configuration for the ExtJS client.
	 *
	 * @return string JSON encoded configuration object
	 */
	protected function _getJsonClientConfig()
	{
		$config = $this->_getContext()->getConfig()->get( 'client/extjs', array() );
		return json_encode( array( 'client' => array( 'extjs' => $config ) ), JSON_FORCE_OBJECT );
	}


	/**
	 * Returns the JSON encoded translations for the ExtJS client.
	 *
	 * @param string $lang ISO language code like "en" or "en_GB"
	 * @return string JSON encoded translation object
	 */
	protected function _getJsonClientI18n( $lang )
	{
		$i18nPaths = Base::getAimeos()->getI18nPaths();
		$i18n = new \MW_Translation_Zend2( $i18nPaths, 'gettext', $lang, array('disableNotices'=>true) );

		$content = array(
			'client/extjs' => $i18n->getAll( 'client/extjs' ),
			'client/extjs/ext' => $i18n->getAll( 'client/extjs/ext' ),
		);

		return json_encode( $content, JSON_FORCE_OBJECT );
	}


	/**
	 * Returns the JSON encoded site item.
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request TYPO3 request object
	 * @return string JSON encoded site item object
	 * @throws Exception If no site item was found for the code
	 */
	protected function _getSite( \TYPO3\CMS\Extbase\Mvc\RequestInterface $request )
	{
		$localeManager = \MShop_Locale_Manager_Factory::createManager( $this->_getContext() );
		$manager = $localeManager->getSubManager( 'site' );

		$site = 'default';
		if( $request->hasArgument( 'site' ) ) {
			$site = $request->getArgument( 'site' );
		}

		$criteria = $manager->createSearch();
		$criteria->setConditions( $criteria->compare( '==', 'locale.site.code', $site ) );
		$items = $manager->searchItems( $criteria );

		if( ( $item = reset( $items ) ) === false ) {
			throw new Exception( sprintf( 'No site found for code "%1$s"', $site ) );
		}

		return json_encode( $item->toArray() );
	}
}
