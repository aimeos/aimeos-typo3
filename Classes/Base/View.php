<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Create Aimeos views
 *
 * @package TYPO3
 */
class View
{
	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URL builder object
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string|null $locale Code of the current language or null for no translation
	 * @param boolean $frontend True if the view is for the frontend, false for the backend
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public static function get( \Aimeos\MShop\Context\Item\Iface $context,
		\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder, array $templatePaths,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $locale = null )
	{
		$obj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Extbase\Object\ObjectManager::class );
		$engines = array( '.html' => new \Aimeos\MW\View\Engine\Typo3( $obj ) );

		$view = new \Aimeos\MW\View\Standard( $templatePaths, $engines );
		$view->prefix = $uriBuilder->getArgumentPrefix();

		$config = $context->getConfig();
		$session = $context->getSession();

		self::addTranslate( $view, $locale, $config->get( 'i18n', array() ) );
		self::addParam( $view, $request );
		self::addConfig( $view, $config );
		self::addNumber( $view, $config );
		self::addFormparam( $view, array( $uriBuilder->getArgumentPrefix() ) );
		self::addUrl( $view, $config, $uriBuilder, $request );
		self::addSession( $view, $session );
		self::addRequest( $view, $request );
		self::addResponse( $view );
		self::addAccess( $view );

		return $view;
	}


	/**
	 * Adds the "access" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addAccess( \Aimeos\MW\View\Iface $view )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_access'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_access'] ) )
		) {
			return $fcn( $view );
		}

		if( TYPO3_MODE === 'BE' )
		{
			if( $GLOBALS['BE_USER']->isAdmin() === false )
			{
				$groups = array();
				foreach( (array) $GLOBALS['BE_USER']->userGroups as $entry ) {
					$groups[] = $entry['title'];
				}
				$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, $groups );
			}
			else
			{
				$helper = new \Aimeos\MW\View\Helper\Access\All( $view );
			}
		}
		else
		{
			if( $GLOBALS['TSFE']->loginUser == 1 ) {
				$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, $GLOBALS['TSFE']->fe_user->groupData['title'] );
			} else {
				$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, array() );
			}
		}

		$view->addHelper( 'access', $helper );

		return $view;
	}


	/**
	 * Adds the "config" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addConfig( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_config'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_config'] ) )
		) {
			return $fcn( $view, $config );
		}

		$conf = new \Aimeos\MW\Config\Decorator\Protect( clone $config, array( 'admin', 'client' ) );
		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $conf );
		$view->addHelper( 'config', $helper );

		return $view;
	}


	/**
	 * Adds the "formparam" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $prefixes List of prefixes for the form name to build multi-dimensional arrays
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addFormparam( \Aimeos\MW\View\Iface $view, array $prefixes )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_formparam'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_formparam'] ) )
		) {
			return $fcn( $view, $prefixes );
		}

		$helper = new \Aimeos\MW\View\Helper\Formparam\Standard( $view, $prefixes );
		$view->addHelper( 'formparam', $helper );

		return $view;
	}


	/**
	 * Adds the "number" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addNumber( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_number'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_number'] ) )
		) {
			return $fcn( $view, $config );
		}

		$sepDec = $config->get( 'client/html/common/format/separatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/separator1000', ' ' );
		$decimals = $config->get( 'client/html/common/format/decimals', 2 );

		$helper = new \Aimeos\MW\View\Helper\Number\Standard( $view, $sepDec, $sep1000, $decimals );
		$view->addHelper( 'number', $helper );

		return $view;
	}


	/**
	 * Adds the "param" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object or null if not available
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addParam( \Aimeos\MW\View\Iface $view, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_param'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_param'] ) )
		) {
			return $fcn( $view, $request );
		}

		$params = ( $request !== null ? $request->getArguments() : array() );
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $params );
		$view->addHelper( 'param', $helper );

		return $view;
	}


	/**
	 * Adds the "request" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addRequest( \Aimeos\MW\View\Iface $view, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_request'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_request'] ) )
		) {
			return $fcn( $view, $request );
		}

		$target = $GLOBALS["TSFE"]->id;
		$get = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$post = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();

		$helper = new \Aimeos\MW\View\Helper\Request\Typo3( $view, $target, $_FILES, $get, $post, $_COOKIE, $_SERVER );
		$view->addHelper( 'request', $helper );

		return $view;
	}


	/**
	 * Adds the "response" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addResponse( \Aimeos\MW\View\Iface $view )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_response'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_response'] ) )
		) {
			return $fcn( $view );
		}

		$helper = new \Aimeos\MW\View\Helper\Response\Typo3( $view );
		$view->addHelper( 'response', $helper );

		return $view;
	}


	/**
	 * Adds the "session" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Session\Iface $session Session object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addSession( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Session\Iface $session )
	{
		$helper = new \Aimeos\MW\View\Helper\Session\Standard( $view, $session );
		$view->addHelper( 'session', $helper );

		return $view;
	}


	/**
	 * Adds the "translate" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param string|null $langid ISO language code, e.g. "de" or "de_CH"
	 * @param array $local Local translations
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addTranslate( \Aimeos\MW\View\Iface $view, $langid, array $local )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_translate'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_translate'] ) )
		) {
			return $fcn( $view, $langid, $local );
		}

		if( $langid )
		{
			$i18n = \Aimeos\Aimeos\Base::getI18n( array( $langid ), $local );
			$translation = $i18n[$langid];
		}
		else
		{
			$translation = new \Aimeos\MW\Translation\None( 'en' );
		}

		$helper = new \Aimeos\MW\View\Helper\Translate\Standard( $view, $translation );
		$view->addHelper( 'translate', $helper );

		return $view;
	}


	/**
	 * Adds the "url" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URI builder object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addUrl( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config,
		\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_url'] )
			&& is_callable( ( $fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_url'] ) )
		) {
			return $fcn( $view, $config, $uriBuilder, $request );
		}

		$fixed = array();

		if( $request )
		{
			$name = $config->get( 'typo3/param/name/site', 'site' );
			if( $request->hasArgument( $name ) === true ) {
				$fixed[$name] = $request->getArgument( $name );
			}

			$name = $config->get( 'typo3/param/name/language', 'locale' );
			if( $request->hasArgument( $name ) === true ) {
				$fixed[$name] = $request->getArgument( $name );
			}

			$name = $config->get( 'typo3/param/name/currency', 'currency' );
			if( $request->hasArgument( $name ) === true ) {
				$fixed[$name] = $request->getArgument( $name );
			}

			$url = new \Aimeos\MW\View\Helper\Url\Typo3( $view, $uriBuilder, $fixed );
		}
		else
		{
			$url = new \Aimeos\MW\View\Helper\Url\T3Cli( $view, $uriBuilder, array() );
		}

		$view->addHelper( 'url', $url );

		return $view;
	}
}
