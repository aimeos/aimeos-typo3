<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2020
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos supplier controller.
 *
 * @package TYPO3
 */
class SupplierController extends AbstractController
{
    /**
     * Renders the supplier detail section.
     */
    public function detailAction()
    {
        try {
            $this->removeMetatags();
            $client = \Aimeos\Client\Html::create($this->context(), 'supplier/detail');
            return $this->getClientOutput($client);
        } catch(\Exception $e) {
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
        if ($e->getCode() > 400 && $e->getCode() < 500) {
            $name = \TYPO3\CMS\Frontend\Controller\ErrorController::class;

            $response = GeneralUtility::makeInstance($name)->pageNotFoundAction(
                $this->request, $e->getMessage(), ['code' => $e->getCode()]
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
