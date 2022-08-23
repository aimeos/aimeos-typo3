<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Abstract class with common functionality for all controllers.
 *
 * @package TYPO3
 */
abstract class AbstractController
    extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
    implements \TYPO3\CMS\Extbase\Mvc\Controller\ControllerInterface
{
    private static $aimeos;
    private static $context;
    private $contextBE;
    private $ceUid;


    /**
     * Checks if the backend user is allowed to access the site
     *
     * @param array $sitePath List of siteid values
     * @param mixed $userSiteId Site ID stored in the backend user record
     * @return bool True if the user is allowed to access the site
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException If user doesn't have access
     */
    protected function checkSite( array $sitePath, $userSiteId ) : bool
    {
        foreach ( array_reverse( $sitePath ) as $siteid )
        {
            if ((string) $userSiteId === (string) $siteid ) {
                return true;
            }
        }

        throw new \TYPO3\CMS\Core\Resource\Exception\InsufficientUserPermissionsException( 'Access not allowed' );
    }


    /**
     * Returns the context item for the frontend
     *
     * @param string $templatePath Path for retrieving the template paths used in the view
     * @param bool $withView True to add view to context object, false for no view
     * @return \Aimeos\MShop\ContextIface Context item
     */
    protected function context( string $templatePath = 'client/html/templates',
        bool $withView = true ) : \Aimeos\MShop\ContextIface
    {
        $config = Base::config( (array) $this->settings );

        if (!isset( self::$context ) )
        {
            $context = Base::context( $config );
            $locale = Base::locale( $context, $this->request );
            $context->setI18n( Base::i18n( [$locale->getLanguageId()], $config->get( 'i18n', [] ) ) );
            $context->setLocale( $locale );

            self::$context = $context;
        }

        // Use plugin specific configuration
        self::$context->setConfig( $config );

        foreach ( self::$context->locale()->getSiteItem()->getConfig() as $key => $value ) {
            $config->set( $key, $value );
        }

        if ($withView === true )
        {
            $langid = self::$context->locale()->getLanguageId();
            $paths = self::$aimeos->getTemplatePaths( $templatePath );
            $view = Base::view( self::$context, $this->uriBuilder, $paths, $this->request, $langid );

            self::$context->setView( $view );
        }

        return self::$context;
    }


    /**
     * Returns the context item for backend operations
     *
     * @param string $templatePath Path for retrieving the template paths used in the view
     * @param bool $withView True to add view to context object, false for no view
     * @return \Aimeos\MShop\ContextIface Context item
     */
    protected function contextBackend( string $templatePath = 'admin/jqadm/templates',
        bool $withView = true ) : \Aimeos\MShop\ContextIface
    {
        if (!isset( $this->contextBE ) )
        {
            $lang = 'en';
            $site = 'default';

            $config = Base::config( (array) $this->settings );
            $context = Base::context( $config );

            if ($this->request->hasArgument( 'locale' ) && ( $value = $this->request->getArgument( 'locale' ) ) != '' ) {
                $lang = $value;
            } elseif (!in_array( $GLOBALS['BE_USER']->uc['lang'] ?? '', ['', 'default'] ) ) {
                $lang = $GLOBALS['BE_USER']->uc['lang'];
            }

            if ($this->request->hasArgument( 'site' ) && ( $value = $this->request->getArgument( 'site' ) ) != '' ) {
                $site = $value;
            } elseif (isset( $GLOBALS['BE_USER']->user['siteid'] ) && $GLOBALS['BE_USER']->user['siteid'] != '' ) {
                $siteManager = \Aimeos\MShop::create( $context, 'locale/site' );
                $siteId = current( array_reverse( explode( '.', trim( $GLOBALS['BE_USER']->user['siteid'], '.' ) ) ) );
                $site = ( $siteId ? $siteManager->get( $siteId )->getCode() : 'default' );
            }

            $locale = Base::getLocaleBackend( $context, $site );
            $context->setLocale( $locale );

            if (isset( $GLOBALS['BE_USER']->user['siteid'] ) && $GLOBALS['BE_USER']->user['siteid'] != '' ) {
                $this->checkSite( $locale->getSitePath(), $GLOBALS['BE_USER']->user['siteid'] );
            }

            $i18n = Base::i18n( [$lang, 'en'], $config->get( 'i18n', [] ) );
            $context->setI18n( $i18n );

            foreach ( $locale->getSiteItem()->getConfig() as $key => $value ) {
                $config->set( $key, $value );
            }

            if ($withView )
            {
                $paths = self::$aimeos->getTemplatePaths( $templatePath );
                $view = Base::view( $context, $this->uriBuilder, $paths, $this->request, $lang );
                $context->setView( $view );
            }

            $this->contextBE = $context;
        }

        return $this->contextBE;
    }


    /**
     * Returns the output of the client and adds the header.
     *
     * @param \Aimeos\Client\Html\Iface $client Html client object (no type hint to prevent reflection)
     * @return string HTML code for inserting into the HTML body
     */
    protected function getClientOutput( \Aimeos\Client\Html\Iface $client )
    {
        $uid = $this->ceUid;
        if ($GLOBALS['TYPO3_REQUEST'] instanceof \Psr\Http\Message\ServerRequestInterface
            && empty( $GLOBALS['TYPO3_REQUEST']->getAttribute( 'routing' ) ) === false
        ) {
            $uid .= '-' . $GLOBALS['TYPO3_REQUEST']->getAttribute( 'routing' )->getPageType();
        }

        $client->setView( $this->context()->view() )->init();
        $header = (string) $client->header( $uid );
        $html = (string) $client->body( $uid );

        if (!isset( $this->responseFactory ) ) // TYPO3 10
        {
            $this->response->addAdditionalHeaderData( $header );
            return $html;
        }

        $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Core\Page\PageRenderer::class );
        $renderer->addHeaderData( $header );

        return $this->responseFactory->createResponse()
            ->withAddedHeader( 'Content-Type', 'text/html; charset=utf-8' )
            ->withBody( $this->streamFactory->createStream( $html ) );
    }


    /**
     * Initializes the object before the real action is called.
     */
    protected function initializeAction()
    {
        $this->uriBuilder->setArgumentPrefix( 'ai' );

        $ce = $this->configurationManager->getContentObject();
        $this->ceUid = $ce->data['uid'] ?? null;

        // initialize bootstrapping
        self::$aimeos = Base::aimeos();
    }


    /**
     * Disables Fluid views for performance reasons
     *
     * return null
     */
    protected function resolveView()
    {
        return null;
    }
}
