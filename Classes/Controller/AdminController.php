<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use \TYPO3\CMS\Backend\Utility\BackendUtility;


/**
 * Controller for adminisration interface.
 *
 * @package TYPO3_Aimeos
 */
class AdminController extends AbstractController
{
	private $context;
	private $controller;


	/**
	 * Generates the index file for the admin interface.
	 */
	public function indexAction()
	{
		$cssHeader = '';
		$abslen = strlen( PATH_site );
		$langid = $this->getContext()->getLocale()->getLanguageId();
		$controller = $this->getController();

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
				$cssHeader .= $jsb2->getHtml( 'css' );
			}
		}

		// rawurldecode() is necessary for ExtJS templates to replace "{site}" properly
		$urlTemplate = rawurldecode( BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeos_web_aimeostxaimeosadmin' => array( 'site' => '{site}', 'tab' => '{tab}' ) ) ) );
		$serviceUrl = BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeos_web_aimeostxaimeosadmin' => array( 'controller' => 'Admin', 'action' => 'do' ) ) );

		$this->view->assign( 'cssHeader', $cssHeader );
		$this->view->assign( 'lang', $langid );
		$this->view->assign( 'i18nContent', $this->getJsonClientI18n( $langid ) );
		$this->view->assign( 'config', $this->getJsonClientConfig() );
		$this->view->assign( 'site', $this->getSite( $this->request ) );
		$this->view->assign( 'smd', $controller->getJsonSmd( $serviceUrl ) );
		$this->view->assign( 'itemSchemas', $controller->getJsonItemSchemas() );
		$this->view->assign( 'searchSchemas', $controller->getJsonSearchSchemas() );
		$this->view->assign( 'activeTab', ( $this->request->hasArgument( 'tab' ) ? (int) $this->request->getArgument( 'tab' ) : 0 ) );
		$this->view->assign( 'version', $this->getVersion() );
		$this->view->assign( 'urlTemplate', $urlTemplate );
	}


	/**
	 * Single entry point for all MShop admin requests.
	 *
	 * @return JSON 2.0 RPC message response
	 */
	public function doAction()
	{
		$param = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
		$this->view->assign( 'response', $this->getController()->process( $param, 'php://input' ) );
	}


	/**
	 * Returns the JS file content
	 *
	 * @return Response Response object
	 */
	public function fileAction()
	{
		$contents = '';
		$jsFiles = array();

		foreach( Base::getAimeos()->getCustomPaths( 'client/extjs' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;
				$jsb2 = new \MW_Jsb2_Default( $jsbAbsPath, dirname( $jsbAbsPath ) );
				$jsFiles = array_merge( $jsFiles, $jsb2->getUrls( 'js', '' ) );
			}
		}

		foreach( $jsFiles as $file )
		{
			if( ( $content = file_get_contents( $file ) ) !== false ) {
				$contents .= $content;
			}
		}

		$response = $this->getControllerContext()->getResponse();
		$response->setHeader( 'Content-Type', 'application/javascript' );
		$response->setContent( $contents );

		return $response;
	}


	/**
	 * Returns the context item
	 *
	 * @return \MShop_Context_Item_Interface Context item
	 */
	protected function getContext()
	{
		if( !isset( $this->context ) )
		{
			$config = $this->getConfig( $this->settings );
			$context = Base::getContext( $config );

			$localeItem = $this->getLocale( $context );
			$context->setLocale( $localeItem );

			$localI18n = ( isset( $this->settings['i18n'] ) ? $this->settings['i18n'] : array() );
			$i18n = Base::getI18n( array( $localeItem->getLanguageId() ), $localI18n );

			$context->setI18n( $i18n );
			$context->setEditor( $GLOBALS['BE_USER']->user['username'] );

			$this->context = $context;
		}

		return $this->context;
	}


	/**
	 * Returns the ExtJS JSON RPC controller
	 *
	 * @return \Controller_ExtJS_JsonRpc ExtJS JSON RPC controller
	 */
	protected function getController()
	{
		if( !isset( $this->controller ) )
		{
			$cntlPaths = Base::getAimeos()->getCustomPaths( 'controller/extjs' );
			$this->controller = new \Controller_ExtJS_JsonRpc( $this->getContext(), $cntlPaths );
		}

		return $this->controller;
	}


	/**
	 * Returns the JSON encoded configuration for the ExtJS client.
	 *
	 * @return string JSON encoded configuration object
	 */
	protected function getJsonClientConfig()
	{
		$conf = $this->getContext()->getConfig()->get( 'client/extjs', array() );
		return json_encode( array( 'client' => array( 'extjs' => $conf ) ), JSON_FORCE_OBJECT );
	}


	/**
	 * Returns the JSON encoded translations for the ExtJS client.
	 *
	 * @param string $lang ISO language code like "en" or "en_GB"
	 * @return string JSON encoded translation object
	 */
	protected function getJsonClientI18n( $lang )
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
	 * Returns the locale object for the context
	 *
	 * @param \MShop_Context_Item_Interface $context Context object
	 * @return \MShop_Locale_Item_Interface Locale item object
	 */
	protected function getLocale( \MShop_Context_Item_Interface $context )
	{
		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->uc['lang'];
		}

		$localeManager = \MShop_Locale_Manager_Factory::createManager( $context );

		try {
			$sitecode = $context->getConfig()->get( 'mshop/locale/site', 'default' );
			$localeItem = $localeManager->bootstrap( $sitecode, $langid, '', false );
		} catch( \MShop_Locale_Exception $e ) {
			$localeItem = $localeManager->createItem();
		}

		$localeItem->setLanguageId( $langid );

		return $localeItem;
	}


	/**
	 * Returns the JSON encoded site item.
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request TYPO3 request object
	 * @return string JSON encoded site item object
	 * @throws Exception If no site item was found for the code
	 */
	protected function getSite( \TYPO3\CMS\Extbase\Mvc\RequestInterface $request )
	{
		$localeManager = \MShop_Locale_Manager_Factory::createManager( $this->getContext() );
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


	/**
	 * Returns the version of the Aimeos TYPO3 extension
	 *
	 * @return string Version string
	 */
	protected function getVersion()
	{
		$match = array();
		$content = @file_get_contents( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'ext_emconf.php' );

		if( preg_match( "/'version' => '([^']+)'/", $content, $match ) === 1 ) {
			return $match[1];
		}

		return '';
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
}
