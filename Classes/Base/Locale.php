<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;

use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos locale class
 *
 * @package TYPO3
 */
class Locale
{
    private static $locale;


    /**
     * Returns the locale object for frontend
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
     * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
     */
    public static function get(\Aimeos\MShop\ContextIface $context,
        \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null) : \Aimeos\MShop\Locale\Item\Iface
    {
        if (!isset(self::$locale)) {
            $config = $context->config();


            $sitecode = $config->get('mshop/locale/site', 'default');
            $name = $config->get('typo3/param/name/site', 'site');

            if ($request !== null && $request->hasArgument($name) === true) {
                $sitecode = $request->getArgument($name);
            }


            $lang = $config->get('mshop/locale/language', '');
            $name = $config->get('typo3/param/name/language', 'locale');

            if ($request !== null && $request->hasArgument($name) === true) {
                $lang = $request->getArgument($name);
            }


            $currency = $config->get('mshop/locale/currency', '');
            $name = $config->get('typo3/param/name/currency', 'currency');

            if ($request !== null && $request->hasArgument($name) === true) {
                $currency = $request->getArgument($name);
            }


            $localeManager = \Aimeos\MShop::create($context, 'locale');
            self::$locale = $localeManager->bootstrap($sitecode, $lang, $currency);

            $config->apply(self::$locale->getSiteItem()->getConfig());
        }

        return self::$locale;
    }


    /**
     * Returns the locale item for the backend
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param string $site Unique site code
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    public static function getBackend(\Aimeos\MShop\ContextIface $context, string $site) : \Aimeos\MShop\Locale\Item\Iface
    {
        $localeManager = \Aimeos\MShop::create($context, 'locale');

        try {
            $localeItem = $localeManager->bootstrap($site, '', '', false, null, true);
            $context->config()->apply($localeItem->getSiteItem()->getConfig());
        } catch(\Aimeos\MShop\Exception $e) {
            $localeItem = $localeManager->create();
        }

        return $localeItem->setCurrencyId(null)->setLanguageId(null);
    }
}
