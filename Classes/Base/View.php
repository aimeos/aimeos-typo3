<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManager;


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
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder|\TYPO3\CMS\Core\Routing\RouterInterface $uriBuilder URL builder
     * @param array $templatePaths List of base path names with relative template paths as key/value pairs
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
     * @param string|null $locale Code of the current language or null for no translation
     * @return \Aimeos\Base\View\Iface View object
     */
    public static function get(\Aimeos\MShop\ContextIface $context, $uriBuilder, array $templatePaths,
        \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, string $locale = null) : \Aimeos\Base\View\Iface
    {
        $configManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $config = $configManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FRAMEWORK);

        $view = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $view->setPartialRootPaths( $config['view']['partialRootPaths'] ?? [] );
        $view->setLayoutRootPaths( $config['view']['layoutRootPaths'] ?? [] );
        $view->setRequest($request);

        $engines = ['.html' => new \Aimeos\Base\View\Engine\Typo3($view)];

        $prefix = 'ai';
        if ($uriBuilder instanceof \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder) {
            $prefix = $uriBuilder->getArgumentPrefix();
        }

        $view = new \Aimeos\Base\View\Standard($templatePaths, $engines);
        $view->prefix = $prefix;

        $config = $context->config();
        $session = $context->session();

        self::addTranslate($view, $locale, $config->get('i18n', []));
        self::addParam($view, $request);
        self::addConfig($view, $config);
        self::addDate($view, $config, $locale);
        self::addFormparam($view, [$prefix]);
        self::addNumber($view, $config, $locale);
        self::addUrl($view, $config, $uriBuilder, $request);
        self::addSession($view, $session);
        self::addRequest($view, $request);
        self::addResponse($view);
        self::addAccess($view);

        return $view;
    }


    /**
     * Adds the "access" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addAccess(\Aimeos\Base\View\Iface $view) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_access'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_access']))
        ) {
            return $fcn($view);
        }

        $appType = null;
        if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof \TYPO3\CMS\Core\Http\ServerRequest) {
            $appType = \TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST']);
        }

        $t3context = GeneralUtility::makeInstance('TYPO3\CMS\Core\Context\Context');

        if ($appType && $appType->isBackend()) {
            if ($t3context->getPropertyFromAspect('backend.user', 'isAdmin', false) === false) {
                $ids = $t3context->getPropertyFromAspect('backend.user', 'getGroupIds', []);
                $names = $t3context->getPropertyFromAspect('backend.user', 'getGroupNames', []);
                $helper = new \Aimeos\Base\View\Helper\Access\Standard($view, array_combine($ids, $names));
            } else {
                $helper = new \Aimeos\Base\View\Helper\Access\All($view);
            }
        } else {
            if ($t3context->getPropertyFromAspect('frontend.user', 'isLoggedIn', false)) {
                $ids = $t3context->getPropertyFromAspect('frontend.user', 'getGroupIds', []);
                $names = $t3context->getPropertyFromAspect('frontend.user', 'getGroupNames', []);
                $helper = new \Aimeos\Base\View\Helper\Access\Standard($view, array_combine($ids, $names));
            } else {
                $helper = new \Aimeos\Base\View\Helper\Access\Standard($view, []);
            }
        }

        $view->addHelper('access', $helper);

        return $view;
    }


    /**
     * Adds the "config" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addConfig(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_config'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_config']))
        ) {
            return $fcn($view, $config);
        }

        $conf = new \Aimeos\Base\Config\Decorator\Protect(clone $config, ['resource/*/baseurl'], ['resource']);
        $helper = new \Aimeos\Base\View\Helper\Config\Standard($view, $conf);
        $view->addHelper('config', $helper);

        return $view;
    }


    /**
     * Adds the "date" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @param string|null $locale (Country specific) language code
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addDate(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config,
        string $locale = null) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_date'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_date']))
        ) {
            return $fcn($view, $config, $locale);
        }

        $format = $config->get('client/html/common/date/format');

        $helper = new \Aimeos\Base\View\Helper\Date\Standard($view, $format);
        $view->addHelper('date', $helper);

        return $view;
    }


    /**
     * Adds the "formparam" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param array $prefixes List of prefixes for the form name to build multi-dimensional arrays
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addFormparam(\Aimeos\Base\View\Iface $view, array $prefixes) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_formparam'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_formparam']))
        ) {
            return $fcn($view, $prefixes);
        }

        $helper = new \Aimeos\Base\View\Helper\Formparam\Standard($view, $prefixes);
        $view->addHelper('formparam', $helper);

        return $view;
    }


    /**
     * Adds the "number" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @param string|null $locale (Country specific) language code
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addNumber(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config,
        string $locale = null) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_number'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_number']))
        ) {
            return $fcn($view, $config, $locale);
        }

        $format = $config->get('client/html/common/number/format');

        $helper = new \Aimeos\Base\View\Helper\Number\Locale($view, $locale ?? 'en', $format);
        $view->addHelper('number', $helper);

        return $view;
    }


    /**
     * Adds the "param" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object or null if not available
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addParam(\Aimeos\Base\View\Iface $view,
        \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_param'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_param']))
        ) {
            return $fcn($view, $request);
        }

        $params = $request ? $request->getArguments() : [];
        $helper = new \Aimeos\Base\View\Helper\Param\Standard($view, $params['ai'] ?? $params);
        $view->addHelper('param', $helper);

        return $view;
    }


    /**
     * Adds the "request" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addRequest(\Aimeos\Base\View\Iface $view,
        \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_request'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_request']))
        ) {
            return $fcn($view, $request);
        }

        $target = $GLOBALS["TSFE"]->id ?? null;

        $helper = new \Aimeos\Base\View\Helper\Request\Typo3($view, $target, $_FILES, $_GET, $_POST, $_COOKIE, $_SERVER);
        $view->addHelper('request', $helper);

        return $view;
    }


    /**
     * Adds the "response" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addResponse(\Aimeos\Base\View\Iface $view) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_response'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_response']))
        ) {
            return $fcn($view);
        }

        $helper = new \Aimeos\Base\View\Helper\Response\Typo3($view);
        $view->addHelper('response', $helper);

        return $view;
    }


    /**
     * Adds the "session" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Session\Iface $session Session object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addSession(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Session\Iface $session) : \Aimeos\Base\View\Iface
    {
        $helper = new \Aimeos\Base\View\Helper\Session\Standard($view, $session);
        $view->addHelper('session', $helper);

        return $view;
    }


    /**
     * Adds the "translate" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param string|null $langid ISO language code, e.g. "de" or "de_CH"
     * @param array $local Local translations
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addTranslate(\Aimeos\Base\View\Iface $view, string $langid = null, array $local) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_translate'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_translate']))
        ) {
            return $fcn($view, $langid, $local);
        }

        if ($langid) {
            $i18n = \Aimeos\Aimeos\Base::i18n([$langid], $local);
            $translation = $i18n[$langid];
        } else {
            $translation = new \Aimeos\Base\Translation\None('en');
        }

        $helper = new \Aimeos\Base\View\Helper\Translate\Standard($view, $translation);
        $view->addHelper('translate', $helper);

        return $view;
    }


    /**
     * Adds the "url" helper to the view object
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @param \Aimeos\Base\Config\Iface $config Configuration object
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder|\TYPO3\CMS\Core\Routing\RouterInterface $uriBuilder URI builder
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    protected static function addUrl(\Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config, $uriBuilder,
        \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null) : \Aimeos\Base\View\Iface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_url'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view_url']))
        ) {
            return $fcn($view, $config, $uriBuilder, $request);
        }

        $fixed = [];

        if ($request) {
            $name = $config->get('typo3/param/name/site', 'site');

            if ($request !== null && $request->hasArgument($name) === true) {
                $fixed[$name] = $request->getArgument($name);
            }


            $name = $config->get('typo3/param/name/language', 'locale');

            if ($request !== null && $request->hasArgument($name) === true) {
                $fixed[$name] = $request->getArgument($name);
            }


            $name = $config->get('typo3/param/name/currency', 'currency');

            if ($request !== null && $request->hasArgument($name) === true) {
                $fixed[$name] = $request->getArgument($name);
            }
        }

        if ($uriBuilder instanceof \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder) {
            $url = new \Aimeos\Base\View\Helper\Url\Typo3($view, $uriBuilder, $fixed);
        } elseif ($uriBuilder instanceof \TYPO3\CMS\Core\Routing\RouterInterface) {
            $url = new \Aimeos\Base\View\Helper\Url\T3Router($view, $uriBuilder, $fixed);
        } else {
            throw new \RuntimeException('No router for generating URLs available');
        }

        $view->addHelper('url', $url);

        return $view;
    }
}
