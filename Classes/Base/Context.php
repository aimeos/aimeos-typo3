<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2019
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Aimeos context class
 *
 * @package TYPO3
 */
class Context
{
    private static $context;


    /**
     * Returns the current context
     *
     * @param \Aimeos\Base\Config\Iface Configuration object
     * @return \Aimeos\MShop\ContextIface Context object
     */
    public static function get(\Aimeos\Base\Config\Iface $config) : \Aimeos\MShop\ContextIface
    {
        if (self::$context === null) {
            // TYPO3 specifc context with password hasher
            $context = new \Aimeos\MShop\Context\Item\Typo3();
            $context->setConfig($config);

            self::addDataBaseManager($context);
            self::addFilesystemManager($context);
            self::addMessageQueueManager($context);
            self::addLogger($context);
            self::addCache($context);
            self::addMailer($context);
            self::addNonce($context);
            self::addProcess($context);
            self::addSession($context);
            self::addHasher($context);
            self::addToken($context);
            self::addUser($context);
            self::addGroups($context);
            self::addDateTime($context);

            self::$context = $context;
        }

        // Use local TS configuration from plugins
        self::$context->setConfig($config);

        return self::$context;
    }


    /**
     * Adds the cache object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object including config
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addCache(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_cache'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_cache']))
        ) {
            return $fcn($context);
        }

        $cacheName = \Aimeos\Aimeos\Base::getExtConfig('cacheName', 'Typo3');
        if (isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->headerNoCache()) {
             $cacheName = 'None';
        }

        switch ($cacheName) {
            case 'None':
                $context->config()->set('client/html/basket/cache/enable', false);
                $cache = \Aimeos\Base\Cache\Factory::create('None', [], null);
                break;

            case 'Typo3':
                $manager = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
                $cache = new \Aimeos\MAdmin\Cache\Proxy\Typo3($context, $manager->getCache('aimeos'));
                break;

            default:
                $cache = new \Aimeos\MAdmin\Cache\Proxy\Standard($context);
        }

        return $context->setCache($cache);
    }


    /**
     * Adds the database manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addDatabaseManager(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_dbm'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_dbm']))
        ) {
            return $fcn($context);
        }

        $dbm = new \Aimeos\Base\DB\Manager\Standard($context->config()->get('resource', []), 'DBAL');
        return $context->setDatabaseManager($dbm);
    }


    /**
     * Adds the filesystem manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addFilesystemManager(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_fsm'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_fsm']))
        ) {
            return $fcn($context);
        }

        $fsm = new \Aimeos\Base\Filesystem\Manager\Standard($context->config()->get('resource', []));
        return $context->setFilesystemManager($fsm);
    }


    /**
     * Adds the password hasher object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addHasher(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_hasher'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_hasher']))
        ) {
            return $fcn($context);
        }

        $factory = GeneralUtility::makeInstance('TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory');
        return $context->setPassword(new \Aimeos\Base\Password\Typo3($factory->getDefaultHashInstance('FE')));
    }


    /**
     * Adds the logger object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addLogger(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_logger'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_logger']))
        ) {
            return $fcn($context);
        }

        return $context->setLogger(\Aimeos\MAdmin::create($context, 'log'));
    }


    /**
     * Adds the mailer object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addMailer(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mailer'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mailer']))
        ) {
            return $fcn($context);
        }

        return $context->setMail(new \Aimeos\Base\Mail\Typo3(function() {
            return GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
        }));
    }


    /**
     * Adds the message queue manager object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addMessageQueueManager(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mqueue'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_mqueue']))
        ) {
            return $fcn($context);
        }

        $mqm = new \Aimeos\Base\MQueue\Manager\Standard($context->config()->get('resource', []));
        return $context->setMessageQueueManager($mqm);
    }


    /**
     * Adds the nonce value for inline JS to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addNonce(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_nounce'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_nounce']))
        ) {
            return $fcn($context);
        }

        return $context->setNonce(base64_encode(random_bytes(16)));
    }


    /**
     * Adds the process object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addProcess(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_process'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_process']))
        ) {
            return $fcn($context);
        }

        $process = new \Aimeos\Base\Process\Pcntl(\Aimeos\Aimeos\Base::getExtConfig('pcntlMax', 4));

        // Reset before child processes are spawned to avoid lost DB connections afterwards
        if (method_exists('\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections') === false
            || $process->isAvailable() === false
        ) {
            $process = new \Aimeos\Base\Process\None();
        }

        return $context->setProcess($process);
    }


    /**
     * Adds the session object to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addSession(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_session'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_session']))
        ) {
            return $fcn($context);
        }

        $class = \TYPO3\CMS\Core\Authentication\CommandLineUserAuthentication::class;

        if (isset($GLOBALS['TSFE']->fe_user)) {
            $session = new \Aimeos\Base\Session\Typo3($GLOBALS['TSFE']->fe_user);
        } elseif (isset($GLOBALS['BE_USER']) && !($GLOBALS['BE_USER'] instanceof $class)) {
            $session = new \Aimeos\Base\Session\Typo3($GLOBALS['BE_USER']);
        } else {
            $session = new \Aimeos\Base\Session\None();
        }

        return $context->setSession($session);
    }


    /**
     * Adds the session token to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addToken(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_token'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_token']))
        ) {
            return $fcn($context);
        }

        $session = $context->session();

        if (($token = $session->get('token')) === null) {
            $session->set('token', isset($GLOBALS['TSFE']->fe_user) ? $GLOBALS['TSFE']->fe_user->id : md5(microtime(true) . getmypid()));
        }

        return $context->setToken($token);
    }


    /**
     * Adds the user ID and editor name to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addUser(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_user'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_user']))
        ) {
            return $fcn($context);
        }

        $appType = null;
        $t3context = GeneralUtility::makeInstance('TYPO3\CMS\Core\Context\Context');

        if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface) {
            $appType = \TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST']);
        }

        if ($appType && $appType->isFrontend() && $t3context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            $context->setUserId($GLOBALS['TSFE']->fe_user->user[$GLOBALS['TSFE']->fe_user->userid_column]);
            $context->setEditor((string) $GLOBALS['TSFE']->fe_user->user['username']);
        } elseif ($appType && $appType->isBackend() && isset($GLOBALS['BE_USER']->user['username'])) {
            $context->setEditor((string) $GLOBALS['BE_USER']->user['username']);
        } else {
            $context->setEditor((string) GeneralUtility::getIndpEnv('REMOTE_ADDR'));
        }

        return $context;
    }


    /**
     * Adds the group IDs to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addGroups(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_groups'])
            && is_callable(($fcn = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context_groups']))
        ) {
            return $fcn($context);
        }

        $t3context = GeneralUtility::makeInstance('TYPO3\CMS\Core\Context\Context');

        $appType = null;
        if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface) {
            $appType = \TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST']);
        }

        if ($appType && $appType->isFrontend() && $t3context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            $ids = GeneralUtility::trimExplode(',', $GLOBALS['TSFE']->fe_user->user['usergroup']);
            $context->setGroupIds($ids);
        } elseif ($appType && $appType->isBackend() && $GLOBALS['BE_USER']->userGroups) {
            $ids = array_keys($GLOBALS['BE_USER']->userGroups);
            $context->setGroupIds($ids);
        }

        return $context;
    }


    /**
     * Adds the frontend date time to the context
     *
     * @param \Aimeos\MShop\ContextIface $context Context object including config
     * @return \Aimeos\MShop\ContextIface Modified context object
     */
    protected static function addDateTime(\Aimeos\MShop\ContextIface $context) : \Aimeos\MShop\ContextIface
    {
        $appType = null;
        if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface) {
            $appType = \TYPO3\CMS\Core\Http\ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST']);
        }

        if ($appType && $appType->isFrontend() && isset($GLOBALS['BE_USER']->adminPanel)
            && class_exists('TYPO3\\CMS\\Adminpanel\\Service\\ConfigurationService')) {
            $service = GeneralUtility::makeInstance('TYPO3\\CMS\\Adminpanel\\Service\\ConfigurationService');
            $tstamp = strtotime($service->getConfigurationOption('preview', 'simulateDate'));

            if (!empty($tstamp)) {
                $context->setDateTime(date('Y-m-d H:i:s', $tstamp));
            }
        }

        return $context;
    }
}
