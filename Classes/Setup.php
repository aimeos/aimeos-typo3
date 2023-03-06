<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


use \Symfony\Component\Console\Output\OutputInterface;
use \TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use \TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;
use \TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use \TYPO3\CMS\Install\Updates\RepeatableInterface;
use \TYPO3\CMS\Install\Updates\ChattyInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Aimeos setup class.
 *
 * @package TYPO3
 */
class Setup implements UpgradeWizardInterface, RepeatableInterface, ChattyInterface
{
    private $output;


    /**
     * Return the identifier for this wizard
     * This should be the same string as used in the ext_localconf class registration
     *
     * @return string
     */
    public function getIdentifier() : string
    {
      return 'aimeos';
    }


    /**
     * Return the speaking name of this wizard
     *
     * @return string
     */
    public function getTitle() : string
    {
      return 'Aimeos database migration';
    }


    /**
     * Return the description for this wizard
     *
     * @return string
     */
    public function getDescription() : string
    {
      return 'Updates the Aimeos database tables and migrates data if necessary';
    }


    /**
     * Execute the update
     *
     * @return bool
     */
    public function executeUpdate() : bool
    {
        try {
            ob_start();
            $exectimeStart = microtime(true);

            self::execute();

            $this->output->writeln(ob_get_clean());
            $this->output->writeln(sprintf('Setup process lasted %1$f sec', (microtime(true) - $exectimeStart)));
        } catch(\Throwable $t) {
            $this->output->writeln(ob_get_clean());
            $this->output->writeln($t->getMessage());
            $this->output->writeln($t->getTraceAsString());

            return false;
        }

        return true;
    }


    /**
     * Returns the classes the upgrade wizard depends on
     *
     * @return string[]
     */
    public function getPrerequisites() : array
    {
        return [];
    }


    /**
     * Setter injection for output into upgrade wizards
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output) : void
    {
        $this->output = $output;
    }


    /**
     * Checks if  update is necessary
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary() : bool
    {
        return true;
    }


    /**
     * Executes the setup tasks for updating the database.
     */
    public static function execute()
    {
        ini_set('max_execution_time', 0);

        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $extconf = $objectManager->get('TYPO3\CMS\Core\Configuration\ExtensionConfiguration');
        $demo = $extconf->get('aimeos', 'useDemoData');

        \Aimeos\MShop::cache(false);
        \Aimeos\MAdmin::cache(false);

        $site = \Aimeos\Aimeos\Base::getExtConfig('siteCode', 'default');
        $template = \Aimeos\Aimeos\Base::getExtConfig('siteTpl', 'default');

        $boostrap = \Aimeos\Aimeos\Base::aimeos();
        $ctx = self::context(['setup' => ['default' => ['demo' => (string) $demo]]])->setEditor('setup');

        \Aimeos\Setup::use($boostrap)->verbose('vvv')
            ->context($ctx->setEditor('aimeos:setup'))
            ->up($site, $template);

        if (defined('TYPO3_version') && version_compare(constant('TYPO3_version'), '11.0.0', '<')) {
            $extconf->set('aimeos', 'useDemoData', '');
        } else {
            $extconf->set('aimeos', ['useDemoData' => '']);
        }
    }


    /**
     * Returns the current schema for the install tool
     *
     * @param array $sql List of SQL statements
     * @return array SQL statements required for the install tool
     */
    public static function schema(array $sql) : array
    {
        $tables = [];
        $conn = self::context()->db();

        foreach (['fe_users_', 'madmin_', 'mshop_'] as $prefix) {
            $result = $conn->create('SHOW TABLES like \'' . $prefix . '%\'')->execute();

            while (($row = $result->fetch(\Aimeos\Base\DB\Result\Base::FETCH_NUM)) !== null) {
                $tables[] = $row[0];
            }
        }

        foreach ($tables as $table) {
            $result = $conn->create('SHOW CREATE TABLE ' . $table)->execute();

            while (($row = $result->fetch(\Aimeos\Base\DB\Result\Base::FETCH_NUM)) !== null) {
                $str = preg_replace('/,[\n ]*CONSTRAINT.+CASCADE/', '', $row[1]);
                $str = preg_replace('/ DEFAULT CHARSET=[^ ;]+/', '', $str);
                $str = preg_replace('/ COLLATE=[^ ;]+/', '', $str);
                $str = str_replace('"', '`', $str);

                $sql[] = $str . ";\n";
            }
        }

        return ['sqlString' => $sql];
    }


    /**
     * For existing installations
     *
     * @param string|null $extname Installed extension name
     */
    public static function executeOnSignal(string $extname = null)
    {
        self::signal($extname);
    }


    /**
     * Update schema if extension is installed
     *
     * @param string|null $extname Installed extension name
     */
    public static function signal(string $extname = null)
    {
        if ($extname === 'aimeos' && \Aimeos\Aimeos\Base::getExtConfig('autoSetup', true)) {
            self::execute();
        }
    }


    /**
     * Alter schema to avoid TYPO3 dropping Aimeos tables
     *
     * @param AlterTableDefinitionStatementsEvent $event Event object
     */
    public function schemaEvent(AlterTableDefinitionStatementsEvent $event)
    {
        $list = self::schema([]);

        foreach ($list['sqlString'] ?? [] as $sql) {
            $event->addSqlData($sql);
        }
    }


    /**
     * Update schema if extension is installed
     *
     * @param AfterPackageActivationEvent $event Event object
     */
    public function setupEvent(AfterPackageActivationEvent $event)
    {
        if ($event->getPackageKey() === 'aimeos' && \Aimeos\Aimeos\Base::getExtConfig('autoSetup', true)) {
            self::execute();
        }
    }


    /**
     * Returns a new context object.
     *
     * @param array $config Nested array of configuration settings
     * @return \Aimeos\MShop\ContextIface Context object
     */
    protected static function context(array $config = []) : \Aimeos\MShop\ContextIface
    {
        $aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('aimeos');

        if (file_exists($aimeosExtPath . '/Resources/Libraries/autoload.php') === true) {
            require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
        }

        $ctx = new \Aimeos\MShop\Context();
        $conf = \Aimeos\Aimeos\Base::config($config);

        $ctx->setConfig($conf);
        $ctx->setDatabaseManager(new \Aimeos\Base\DB\Manager\Standard($conf->get('resource', []), 'DBAL'));
        $ctx->setFilesystemManager(new \Aimeos\Base\Filesystem\Manager\Standard($conf->get('resource', [])));
        $ctx->setLogger(new \Aimeos\Base\Logger\Errorlog(\Aimeos\Base\Logger\Iface::INFO));
        $ctx->setSession(new \Aimeos\Base\Session\None());
        $ctx->setCache(new \Aimeos\Base\Cache\None());

        // Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
        if (php_sapi_name() === 'cli' && class_exists('\TYPO3\CMS\Core\Database\ConnectionPool')
            && method_exists('\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections')
        ) {
            $ctx->setProcess(new \Aimeos\Base\Process\Pcntl(\Aimeos\Aimeos\Base::getExtConfig('pcntlMax', 4)));
        } else {
            $ctx->setProcess(new \Aimeos\Base\Process\None());
        }

        $factory = GeneralUtility::makeInstance('TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory');
        return $ctx->setPassword(new \Aimeos\Base\Password\Typo3($factory->getDefaultHashInstance('FE')));
    }
}
