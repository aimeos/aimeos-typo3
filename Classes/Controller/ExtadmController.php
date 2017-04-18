<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use \TYPO3\CMS\Backend\Utility\BackendUtility;


/**
 * Controller for ExtJS adminisration interface.
 *
 * @package TYPO3
 */
class ExtadmController extends AbstractController
{
	private $context;
	private $controller;


	/**
	 * Generates the index file for the admin interface.
	 */
	public function indexAction()
	{
		$cssFiles = array();
		$abslen = strlen( PATH_site );
		$controller = $this->getController();

		$locale = $this->getContextBackend()->getLocale();
		$site = $locale->getSite()->getCode();
		$langid = 'en';

		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->uc['lang'];
		}


		foreach( Base::getAimeos()->getCustomPaths( 'admin/extjs' ) as $base => $paths )
		{
			$relJsbPath = '../' . substr( $base, $abslen );

			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;

				if( !is_file( $jsbAbsPath ) ) {
					throw new \RuntimeException( sprintf( 'JSB2 file "%1$s" not found', $jsbAbsPath ) );
				}

				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, '' );
				$cssFiles = array_merge( $cssFiles, $jsb2->getUrls( 'css' ) );
			}
		}

		// rawurldecode() is necessary for ExtJS templates to replace "{site}" properly
		$urlTemplate = rawurldecode( BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeos_web_aimeostxaimeosadmin' => array( 'controller' => 'Extadm', 'action' => 'index', 'site' => '{site}', 'tab' => '{tab}' ) ) ) );
		$serviceUrl = BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeos_web_aimeostxaimeosadmin' => array( 'controller' => 'Extadm', 'action' => 'do' ) ) );
		$jqadmUrl = BackendUtility::getModuleUrl( $this->request->getPluginName(), array( 'tx_aimeos_web_aimeostxaimeosadmin' => array( 'controller' => 'Jqadm', 'action' => 'search', 'site' => $site, 'lang' => $lang, 'resource' => 'dashboard' ) ) );

		$this->view->assign( 'cssFiles', $cssFiles );
		$this->view->assign( 'lang', $langid );
		$this->view->assign( 'i18nContent', $this->getJsonClientI18n( $langid ) );
		$this->view->assign( 'config', $this->getJsonClientConfig() );
		$this->view->assign( 'site', $this->getSite( $this->request ) );
		$this->view->assign( 'smd', $controller->getJsonSmd( $serviceUrl ) );
		$this->view->assign( 'itemSchemas', $controller->getJsonItemSchemas() );
		$this->view->assign( 'searchSchemas', $controller->getJsonSearchSchemas() );
		$this->view->assign( 'activeTab', ( $this->request->hasArgument( 'tab' ) ? (int) $this->request->getArgument( 'tab' ) : 0 ) );
		$this->view->assign( 'extensions', implode( ',', Base::getAimeos()->getExtensions() ) );
		$this->view->assign( 'version', Base::getVersion() );
		$this->view->assign( 'urlTemplate', $urlTemplate );
		$this->view->assign( 'jqadmurl', $jqadmUrl );
	}


	/**
	 * Single entry point for all MShop admin requests.
	 *
	 * @return JSON 2.0 RPC message response
	 */
	public function doAction()
	{
		$param = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();

		if( ( $content = file_get_contents( 'php://input' ) ) === false ) {
			throw new \RuntimeException( 'Unable to get request content' );
		}

		$this->view->assign( 'response', $this->getController()->process( $param, $content ) );
	}


	/**
	 * Returns the JS file content
	 *
	 * @return string Javascript files content
	 */
	public function fileAction()
	{
		$contents = '';
		$jsFiles = array();

		foreach( Base::getAimeos()->getCustomPaths( 'admin/extjs' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;
				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, dirname( $jsbAbsPath ) );
				$jsFiles = array_merge( $jsFiles, $jsb2->getFiles( 'js' ) );
			}
		}

		foreach( $jsFiles as $file )
		{
			if( ( $content = file_get_contents( $file ) ) === false ) {
				throw new \RuntimeException( sprintf( 'File "%1$s" not found', $jsbAbsPath ) );
			}

			$contents .= $content;
		}

		$this->response->setHeader( 'Content-Type', 'application/javascript' );

		return $contents;
	}


	/**
	 * Returns the ExtJS JSON RPC controller
	 *
	 * @return \Aimeos\Controller\ExtJS\JsonRpc ExtJS JSON RPC controller
	 */
	protected function getController()
	{
		if( !isset( $this->controller ) )
		{
			$cntlPaths = Base::getAimeos()->getCustomPaths( 'controller/extjs' );
			$this->controller = new \Aimeos\Controller\ExtJS\JsonRpc( $this->getContextBackend(), $cntlPaths );
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
		$conf = $this->getContextBackend()->getConfig()->get( 'admin/extjs', array() );
		return json_encode( array( 'admin' => array( 'extjs' => $conf ) ), JSON_FORCE_OBJECT );
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
		$i18n = new \Aimeos\MW\Translation\Gettext( $i18nPaths, $lang );

		$content = array(
			'admin' => $i18n->getAll( 'admin' ),
			'admin/ext' => $i18n->getAll( 'admin/ext' ),
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
	protected function getSite( \TYPO3\CMS\Extbase\Mvc\RequestInterface $request )
	{
		$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $this->getContextBackend() );
		$manager = $localeManager->getSubManager( 'site' );

		$site = 'default';
		if( $request->hasArgument( 'site' ) && $request->getArgument( 'site' ) != '' ) {
			$site = $request->getArgument( 'site' );
		}

		$criteria = $manager->createSearch();
		$criteria->setConditions( $criteria->compare( '==', 'locale.site.code', $site ) );
		$items = $manager->searchItems( $criteria );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \RuntimeException( sprintf( 'No site found for code "%1$s"', $site ) );
		}

		return json_encode( $item->toArray() );
	}


	/**
	 * Uses default view.
	 *
	 * return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface View object
	 */
	protected function resolveView()
	{
		return \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::resolveView();
	}
}
