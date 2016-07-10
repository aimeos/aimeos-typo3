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
	public function get( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder,
		array $templatePaths, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $locale = null, $frontend = true )
	{
		$params = $fixed = array();
		$config = $context->getConfig();
		$baseurl = $config->get( 'typo3/baseurl', '/' );

		if( $request !== null && $locale !== null )
		{
			$params = $request->getArguments();
			$fixed = $this->getFixedParams( $config, $request );

			$i18n = \Aimeos\Aimeos\Base::getI18n( array( $locale ), $config->get( 'i18n', array() ) );
			$translation = $i18n[$locale];
		}
		else
		{
			$translation = new \Aimeos\MW\Translation\None( 'en' );
		}


		$view = new \Aimeos\MW\View\Standard( $templatePaths );

		$helper = new \Aimeos\MW\View\Helper\Translate\Standard( $view, $translation );
		$view->addHelper( 'translate', $helper );

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $params );
		$view->addHelper( 'param', $helper );

		$conf = new \Aimeos\MW\Config\Decorator\Protect( clone $config, array( 'admin', 'client' ) );
		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $conf );
		$view->addHelper( 'config', $helper );

		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$helper = new \Aimeos\MW\View\Helper\Number\Standard( $view, $sepDec, $sep1000 );
		$view->addHelper( 'number', $helper );

		$helper = new \Aimeos\MW\View\Helper\Formparam\Standard( $view, array( $uriBuilder->getArgumentPrefix() ) );
		$view->addHelper( 'formparam', $helper );

		$view->addHelper( 'url', $this->getUrlHelper( $view, $uriBuilder, $request, $baseurl, $fixed ) );

		$files = ( is_array( $_FILES ) ? $_FILES : array() );
		$cookie = ( is_array( $_COOKIE ) ? $_COOKIE : array() );
		$server = ( is_array( $_SERVER ) ? $_SERVER : array() );
		$get = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$post = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();

		$helper = new \Aimeos\MW\View\Helper\Request\Typo3( $view, $target, $files, $get, $post, $cookie, $server );
		$view->addHelper( 'request', $helper );

		$helper = new \Aimeos\MW\View\Helper\Response\Typo3( $view );
		$view->addHelper( 'response', $helper );

		if( $frontend === true ) {
			$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, $this->getGroups( $context ) );
		} else {
			$helper = new \Aimeos\MW\View\Helper\Access\All( $view );
		}
		$view->addHelper( 'access', $helper );

		return $view;
	}


	/**
	 * Returns the fixed parameters that should be included in every URL
	 *
	 * @param \Aimeos\MW\Config\Iface $config Config object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request Request object
	 * @return array Associative list of site, language and currency if available
	 */
	protected function getFixedParams( \Aimeos\MW\Config\Iface $config,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request )
	{
		$fixed = array();

		$name = $config->get( 'typo3/param/name/site', 'loc_site' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/language', 'loc_locale' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/currency', 'loc_currency' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		return $fixed;
	}


	/**
	 * Returns the closure for retrieving the user groups
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Closure Function which returns the user group codes
	 */
	protected function getGroups( \Aimeos\MShop\Context\Item\Iface $context )
	{
		return function() use ( $context )
		{
			$list = array();
			$manager = \Aimeos\MShop\Factory::createManager( $context, 'customer/group' );

			$search = $manager->createSearch();
			$search->setConditions( $search->compare( '==', 'customer.group.id', $context->getGroupIds() ) );

			foreach( $manager->searchItems( $search ) as $item ) {
				$list[] = $item->getCode();
			}

			return $list;
		};
	}


	/**
	 * Creates the URL view helper
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URL builder object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string $baseurl URL of the web site
	 * @param array $fixed Associative list of parameters that are always part of the URL
	 * @return \Aimeos\MW\View\Helper\Url\Iface URL view helper
	 */
	protected function getUrlHelper( \Aimeos\MW\View\Iface $view, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $baseurl = '', array $fixed = array() )
	{
		if( $request === null ) {
			return new \Aimeos\MW\View\Helper\Url\T3Cli( $view, $baseurl, $uriBuilder->getArgumentPrefix(), $fixed );
		}

		return new \Aimeos\MW\View\Helper\Url\Typo3( $view, $uriBuilder, $fixed );
	}
}
