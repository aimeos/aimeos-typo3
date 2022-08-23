<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos catalog controller.
 *
 * @package TYPO3
 */
class CatalogController extends AbstractController
{
    /**
     * Renders the catalog attribute section.
     */
    public function attributeAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/attribute');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog counts.
     */
    public function countAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/count');

        if (!isset($this->responseFactory)) { // TYPO3 10
            return $this->getClientOutput($client);
        }

        $client->setView($this->context()->view())->init();

        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'application/javascript')
            ->withAddedHeader('Cache-Control', 'public, max-age=300')
            ->withBody($this->streamFactory->createStream((string) $client->body()));
    }


    /**
     * Renders the catalog detail section.
     */
    public function detailAction()
    {
        try
        {
            $this->removeMetatags();
            $client = \Aimeos\Client\Html::create($this->context(), 'catalog/detail');
            return $this->getClientOutput($client);
        }
        catch(\Exception $e)
        {
            $this->exception($e);
        }
    }


    /**
     * Renders the catalog filter section.
     */
    public function filterAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/filter');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog home.
     */
    public function homeAction()
    {
        $this->removeMetatags();
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/home');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog list section.
     */
    public function listAction()
    {
        $this->removeMetatags();
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/lists');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog price section.
     */
    public function priceAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/price');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog search section.
     */
    public function searchAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/search');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the user session related catalog section.
     */
    public function sessionAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/session');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog stage section.
     */
    public function stageAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/stage');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog stock section.
     */
    public function stockAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/stock');

        if (!isset($this->responseFactory)) { // TYPO3 10
            return $this->getClientOutput($client);
        }

        $client->setView($this->context()->view())->init();

        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'application/javascript')
            ->withAddedHeader('Cache-Control', 'public, max-age=300')
            ->withBody($this->streamFactory->createStream((string) $client->body()));
    }


    /**
     * Renders a list of product names in JSON format.
     */
    public function suggestAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/suggest');

        if (!isset($this->responseFactory)) { // TYPO3 10
            return $this->getClientOutput($client);
        }

        $client->setView($this->context()->view())->init();

        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'application/json')
            ->withAddedHeader('Cache-Control', 'public, max-age=300')
            ->withBody($this->streamFactory->createStream((string) $client->body()));
    }


    /**
     * Renders the catalog supplier section.
     */
    public function supplierAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'catalog/supplier');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the catalog tree section.
     */
    public function treeAction()
    {
        try
        {
            $client = \Aimeos\Client\Html::create($this->context(), 'catalog/tree');
            return $this->getClientOutput($client);
        }
        catch(\Exception $e)
        {
            $this->exception($e);
        }
    }


    /**
     * Handles exceptions
     *
     * @param \Exception $e Caught exception
     * @throws \Exception Thrown exception
     */
    protected function exception(\Exception $e)
    {
        if ($e->getCode() > 400)
        {
            $name = \TYPO3\CMS\Frontend\Controller\ErrorController::class;

            $response = GeneralUtility::makeInstance($name)->pageNotFoundAction(
                $this->request, $e->getMessage(), ['code' => $e->getCode]
            );
            throw new \TYPO3\CMS\Core\Http\ImmediateResponseException($response);
        }

        throw $e;
    }


    /**
     * Removes the meta tags if available
     */
    protected function removeMetatags()
    {
        if (is_object($GLOBALS['TSFE']) && isset($GLOBALS['TSFE']->config['config']))
        {
            $GLOBALS['TSFE']->config['config']['disableCanonical'] = true;
            $GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
        }

        if (class_exists('\TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry')
            && !\Aimeos\Aimeos\Base::getExtConfig('typo3Metatags', true)
        ) {
            $registry = GeneralUtility::makeInstance('TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry');

            $registry->getManagerForProperty('keywords')->removeProperty('keywords');
            $registry->getManagerForProperty('description')->removeProperty('description');
            $registry->getManagerForProperty('og:type')->removeProperty('og:type');
            $registry->getManagerForProperty('og:title')->removeProperty('og:title');
            $registry->getManagerForProperty('og:url')->removeProperty('og:url');
            $registry->getManagerForProperty('og:description')->removeProperty('og:description');
            $registry->getManagerForProperty('og:image')->removeProperty('og:image');
            $registry->getManagerForProperty('twitter:card')->removeProperty('twitter:card');
        }
    }
}
