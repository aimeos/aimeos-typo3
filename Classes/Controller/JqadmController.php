<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use \Aimeos\Aimeos\Base;


/**
 * Controller for the JSON API
 *
 * @package TYPO3
 */
class JqadmController extends AbstractController
{
	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$this->uriBuilder->setArgumentPrefix( 'tx_aimeos_web_aimeostxaimeosadmin' );
	}


	/**
	 * Returns the CSS/JS file content
	 *
	 * @return string CSS/JS files content
	 */
	public function fileAction()
	{
		$contents = '';
		$files = array();
		$type = $this->request->getArgument( 'type' );

		foreach( Base::getAimeos()->getCustomPaths( 'admin/jqadm' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;
				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, dirname( $jsbAbsPath ) );
				$files = array_merge( $files, $jsb2->getFiles( $type ) );
			}
		}

		foreach( $files as $file )
		{
			if( ( $content = file_get_contents( $file ) ) === false ) {
				throw new \Exception( sprintf( 'File "%1$s" not found', $jsbAbsPath ) );
			}

			$contents .= $content;
		}

		if( $type === 'js' ) {
			$this->response->setHeader( 'Content-Type', 'application/javascript' );
		} elseif( $type === 'css' ) {
			$this->response->setHeader( 'Content-Type', 'text/css' );
		}

		return $contents;
	}


	/**
	 * Returns the HTML code for a copy of a resource object
	 *
	 * @return string Generated output
	 */
	public function copyAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( $cntl->copy() );
	}


	/**
	 * Returns the HTML code for a new resource object
	 *
	 * @return string Generated output
	 */
	public function createAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( $cntl->create() );
	}


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @return string Generated output
	 */
	public function deleteAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( $cntl->delete() . $cntl->search() );
	}


	/**
	 * Returns the HTML code for the requested resource object
	 *
	 * @return string Generated output
	 */
	public function getAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( $cntl->get() );
	}


	/**
	 * Saves a new resource object
	 *
	 * @return string Generated output
	 */
	public function saveAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( ( $cntl->save() ? : $cntl->search() ) );
	}


	/**
	 * Returns the HTML code for a list of resource objects
	 *
	 * @return string Generated output
	 */
	public function searchAction()
	{
		$cntl = $this->createClient();
		return $this->setHtml( $cntl->search() );
	}


	/**
	 * Returns the resource controller
	 *
	 * @return \Aimeos\Admin\JQAdm\Iface JQAdm client
	 */
	protected function createClient()
	{
		$lang = 'en';
		$site = 'default';
		$resource = 'product';

		if( $this->request->hasArgument( 'resource' ) ) {
			$resource = $this->request->getArgument( 'resource' );
		}

		if( $this->request->hasArgument( 'site' ) ) {
			$site = $this->request->getArgument( 'site' );
		}

		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$lang = $GLOBALS['BE_USER']->uc['lang'];
		}

		$templatePaths = Base::getAimeos()->getCustomPaths( 'admin/jqadm/templates' );

		$config = $this->getConfig();
		$context = Base::getContext( $config );
		$context = $this->setLocale( $context, $site, $lang );

		$view = Base::getView( $context, $this->uriBuilder, $templatePaths, $this->request, $lang, false );
		$context->setView( $view );

		return \Aimeos\Admin\JQAdm\Factory::createClient( $context, $templatePaths, $resource );
	}


	/**
	 * Adds the generated HTML code to the view
	 *
	 * @param string $content Content from admin client
	 */
	protected function setHtml( $content )
	{
		$content = str_replace( ['{type}', '{version}'], ['TYPO3', Base::getVersion()], $content );

		$this->view->assign( 'formparam', 'tx_aimeos_web_aimeostxaimeosadmin' );
		$this->view->assign( 'content', $content );
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
	 * Sets the locale item in the given context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @param string $lang ISO language code, e.g. "en" or "en_GB"
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected function setLocale( \Aimeos\MShop\Context\Item\Iface $context, $sitecode, $lang )
	{
		$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		try
		{
			$localeItem = $localeManager->bootstrap( $sitecode, '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );
		}
		catch( \Aimeos\MShop\Locale\Exception $e )
		{
			$localeItem = $localeManager->createItem();
		}

		$context->setLocale( $localeItem );

		$localI18n = ( isset( $this->settings['i18n'] ) ? $this->settings['i18n'] : array() );
		$context->setI18n( Base::getI18n( array( $lang, 'en' ), $localI18n ) );

		return $context;
	}
}
