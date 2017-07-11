<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


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
				throw new \RuntimeException( sprintf( 'File "%1$s" not found', $jsbAbsPath ) );
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

		if( ( $html = $cntl->delete() ) == '' ) {
			return $this->setPsrResponse( $cntl->getView()->response() );
		}

		return $this->getHtml( $html );
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

		if( ( $html = $cntl->save() ) == '' ) {
			return $this->setPsrResponse( $cntl->getView()->response() );
		}

		return $this->getHtml( $html );
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
		$resource = 'dashboard';

		if( $this->request->hasArgument( 'resource' )
			&& ( $value = $this->request->getArgument( 'resource' ) ) != ''
		) {
			$resource = $value;
		}

		$aimeos = Base::getAimeos();
		$templatePaths = $aimeos->getCustomPaths( 'admin/jqadm/templates' );
		$context = $this->getContextBackend( $templatePaths );

		return \Aimeos\Admin\JQAdm\Factory::createClient( $context, $templatePaths, $resource )->setAimeos( $aimeos );
	}


	/**
	 * Adds the generated HTML code to the view
	 *
	 * @param string $content Content from admin client
	 */
	protected function setHtml( $content )
	{
		$version = Base::getVersion();
		$extnames = implode( ',', Base::getAimeos()->getExtensions() );
		$content = str_replace( ['{type}', '{version}', '{extensions}'], ['TYPO3', $version, $extnames], $content );

		$this->view->assign( 'formparam', 'tx_aimeos_web_aimeostxaimeosadmin' );
		$this->view->assign( 'content', $content );
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


	/**
	 * Set the response data from a PSR-7 response object and returns the message content
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response PSR-7 response object
	 * @return string Generated output
	 */
	protected function setPsrResponse( \Psr\Http\Message\ResponseInterface $response )
	{
		$this->response->setStatus( $response->getStatusCode() );

		foreach( $response->getHeaders() as $key => $value ) {
			foreach( (array) $value as $val ) {
				$this->response->setHeader( $key, $val );
			}
		}

		return (string) $response->getBody();
	}
}
