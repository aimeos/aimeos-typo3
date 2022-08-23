<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos checkout controller.
 *
 * @package TYPO3
 */
class CheckoutController extends AbstractController
{
    /**
     * Processes requests and renders the checkout process.
     */
    public function indexAction()
    {
        $client = \Aimeos\Client\Html::create( $this->context(), 'checkout/standard' );
        return $this->getClientOutput( $client );
    }


    /**
     * Processes requests and renders the checkout confirmation.
     */
    public function confirmAction()
    {
        $context = $this->context();
        $client = \Aimeos\Client\Html::create( $context, 'checkout/confirm' );

        $view = $context->view();
        $param = array_merge( GeneralUtility::_GET(), GeneralUtility::_POST() );
        $helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
        $view->addHelper( 'param', $helper );

        $client->setView( $view )->init();

        $header = (string) $client->header();
        $html = (string) $client->body();

        if( !isset( $this->responseFactory ) ) // TYPO3 10
        {
            $this->response->addAdditionalHeaderData( $header );
            return $html;
        }

        GeneralUtility::makeInstance( \TYPO3\CMS\Core\Page\PageRenderer::class )->addHeaderData( $header );

        return $this->responseFactory->createResponse()
            ->withAddedHeader( 'Content-Type', 'text/html; charset=utf-8' )
            ->withBody( $this->streamFactory->createStream( $html ) );
    }


    /**
     * Processes update requests from payment service providers.
     */
    public function updateAction()
    {
        try
        {
            $context = $this->context();
            $client = \Aimeos\Client\Html::create( $context, 'checkout/update' );

            $view = $context->view();
            $param = array_merge( GeneralUtility::_GET(), GeneralUtility::_POST() );
            $helper = new \Aimeos\Base\View\Helper\Param\Standard( $view, $param );
            $view->addHelper( 'param', $helper );

            $client->setView( $view )->init();

            $header = (string) $client->header();
            $html = (string) $client->body();

            if( !isset( $this->responseFactory ) ) // TYPO3 10
            {
                $this->response->addAdditionalHeaderData( $header );
                return $html;
            }

            GeneralUtility::makeInstance( \TYPO3\CMS\Core\Page\PageRenderer::class )->addHeaderData( $header );

            return $this->responseFactory->createResponse()
                ->withBody( $this->streamFactory->createStream( $html ) );
        }
        catch( \Exception $e )
        {
            if( !isset( $this->responseFactory ) ) // TYPO3 10
            {
                @header( 'HTTP/1.1 500 Internal server error', true, 500 );
                return 'Error: ' . $e->getMessage();
            }

            return $this->responseFactory->createResponse()->withStatus( 500 )
                ->withBody( $this->streamFactory->createStream( 'Error: ' . $e->getMessage() ) );
        }
    }
}
