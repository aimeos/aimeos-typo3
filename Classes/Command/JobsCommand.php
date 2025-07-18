<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2018-2019
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Command;


use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Executes the job controllers
 *
 * @package TYPO3
 */
class JobsCommand extends Command
{
    protected static $defaultName = 'aimeos:jobs';


    /**
     * Configures the command name and description
     */
    protected function configure()
    {
        $this->setName(self::$defaultName);
        $this->setDescription('Executes the job controllers');
        $this->addArgument('jobs', InputArgument::REQUIRED, 'One or more job controller names like "admin/job customer/email/watch"');
        $this->addArgument('site', InputArgument::OPTIONAL, 'Site codes to execute the jobs for like "default unittest" (none for all)');
        $this->addOption('pid', null, InputOption::VALUE_REQUIRED, 'Page ID of the catalog detail page for jobs generating URLs');
        $this->addOption('option', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Optional configuration, name and value are separated by ":" like "setup/default/demo:1"', []);
    }


    /**
     * Executes the job controllers
     *
     * @param InputInterface $input Input object
     * @param OutputInterface $output Output object
     * @return Status code
     */
    #[\ReturnTypeWillChange]
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->environment();

        $context = $this->context();
        $context = $this->addConfig($context, $input->getOption('option'));
        $process = $context->process();

        $aimeos = \Aimeos\Aimeos\Base::aimeos();
        $jobs = explode(' ', $input->getArgument('jobs'));
        $localeManager = \Aimeos\MShop::create($context, 'locale');

        foreach ($this->getSiteItems($context, $input) as $siteItem) {
            $localeItem = $localeManager->bootstrap($siteItem->getCode(), '', '', false);
            $localeItem->setLanguageId(null);
            $localeItem->setCurrencyId(null);
            $context->setLocale($localeItem);

            $config = $context->config();
            foreach ($localeItem->getSiteItem()->getConfig() as $key => $value) {
                $config->set($key, $value);
            }

            $tmplPaths = $aimeos->getTemplatePaths('controller/jobs/templates', $localeItem->getSiteItem()->getTheme());
            $view = \Aimeos\Aimeos\Base::view($context, $this->getRouter($input->getOption('pid')), $tmplPaths);
            $context->setView($view);

            $output->writeln(sprintf('Executing the Aimeos jobs for "<info>%s</info>"', $siteItem->getCode()));

            // Reset before child processes are spawned to avoid lost DB connections afterwards (TYPO3 9.4 and above)
            if (method_exists('\TYPO3\CMS\Core\Database\ConnectionPool', 'resetConnections')) {
                GeneralUtility::makeInstance('TYPO3\CMS\Core\Database\ConnectionPool')->resetConnections();
            }

            foreach ($jobs as $jobname) {
                $fcn = function($context, $aimeos, $jobname) {
                    \Aimeos\Controller\Jobs::create($context, $aimeos, $jobname)->run();
                };

                $process->start($fcn, [$context, $aimeos, $jobname], true);
            }
        }

        $process->wait();

        return 0;
    }


    /**
     * Adds the configuration options from the input object to the given context
     *
     * @param array|string $options Input object
     * @param \Aimeos\MShop\ContextIface $ctx Context object
     * @return array Associative list of key/value pairs of configuration options
     */
    protected function addConfig(\Aimeos\MShop\ContextIface $ctx, $options) : \Aimeos\MShop\ContextIface
    {
        $config = $ctx->config();

        foreach ((array) $options as $option) {
            list($name, $value) = explode(':', $option);
            $config->set($name, $value);
        }

        return $ctx;
    }


    /**
     * Returns a context object
     *
     * @return \Aimeos\MShop\ContextIface Context object containing only the most necessary dependencies
     */
    protected function context() : \Aimeos\MShop\ContextIface
    {
        $aimeos = \Aimeos\Aimeos\Base::aimeos();
        $config = \Aimeos\Aimeos\Base::config();
        $context = \Aimeos\Aimeos\Base::context($config);

        $langManager = \Aimeos\MShop::create($context, 'locale/language');
        $langids = $langManager->search($langManager->filter(true))->keys()->toArray();
        $i18n = \Aimeos\Aimeos\Base::i18n($langids, $config->get('i18n', []));

        $context->setSession(new \Aimeos\Base\Session\None());
        $context->setCache(new \Aimeos\Base\Cache\None());

        $context->setEditor('aimeos:jobs');
        $context->setI18n($i18n);

        return $context;
    }


    /**
     * Workaround to fix TYPO3 13.x+ environment for CLI
     */
    protected function environment()
    {
        // \TYPO3\CMS\Core\Core\Bootstrap::initializeBackendAuthentication();
        $type = \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::REQUESTTYPE_BE;
        $cmiface = \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class;

        $cm = GeneralUtility::makeInstance($cmiface);
        $cm->setRequest((new \TYPO3\CMS\Core\Http\ServerRequest())->withAttribute('applicationType', $type));
    }


    /**
     * Returns the enabled site items which may be limited by the input arguments.
     *
     * @param \Aimeos\MShop\ContextIface $context Context item object
     * @param InputInterface $input Input object
     * @return \Aimeos\Map List of site items implementing \Aimeos\MShop\Locale\Item\Site\Iface
     */
    protected function getSiteItems(\Aimeos\MShop\ContextIface $context, InputInterface $input) : \Aimeos\Map
    {
        $manager = \Aimeos\MShop::create($context, 'locale/site');
        $search = $manager->filter();

        if (($codes = (string) $input->getArgument('site')) !== '') {
            $search->setConditions($search->compare('==', 'locale.site.code', explode(' ', $codes)));
        }

        return $manager->search($search);
    }


    /**
     * Returns the page router
     *
     * @param string|null $pid Page ID
     * @return \TYPO3\CMS\Core\Routing\RouterInterface Page router
     * @throws \RuntimeException If no site configuraiton is available
     */
    protected function getRouter(?string $pid) : \TYPO3\CMS\Core\Routing\RouterInterface
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $pid ? $siteFinder->getSiteByPageId($pid) : current($siteFinder->getAllSites());

        if ($site) {
            return $site->getRouter();
        }

        throw new \RuntimeException('No site configuration found');
    }
}
