<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Scheduler\Task;

use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Aimeos e-mail scheduler.
 *
 * @package TYPO3
 */
class Email6 extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    private string $fieldSite = 'aimeos_sitecode';
    private string $fieldTSconfig = 'aimeos_config';
    private string $fieldController = 'aimeos_controller';
    private string $fieldSenderFrom = 'aimeos_sender_from';
    private string $fieldSenderEmail = 'aimeos_sender_email';
    private string $fieldReplyEmail = 'aimeos_reply_email';
    private string $fieldPageLogin = 'aimeos_pageid_login';
    private string $fieldPageDetail = 'aimeos_pageid_detail';
    private string $fieldPageCatalog = 'aimeos_pageid_catalog';
    private string $fieldPageDownload = 'aimeos_pageid_download';

    public array $aimeos_sitecode = [];
    public string $aimeos_config = '';
    public array $aimeos_controller = [];
    public string $aimeos_sender_from = '';
    public string $aimeos_sender_email = '';
    public string $aimeos_reply_email = '';
    public string $aimeos_pageid_login = '';
    public string $aimeos_pageid_detail = '';
    public string $aimeos_pageid_catalog = '';
    public string $aimeos_pageid_download = '';


    /**
     * Executes the configured tasks.
     *
     * @return bool True if success
     * @throws Exception If an error occurs
     */
    public function execute()
    {
        $conf = [];

        if (!isset($conf['client']['html']['catalog']['detail']['url']['config']['absoluteUri'])) {
            $conf['client']['html']['catalog']['detail']['url']['config']['absoluteUri'] = 1;
        }

        if (!isset($conf['client']['html']['account']['download']['url']['config']['absoluteUri'])) {
            $conf['client']['html']['account']['download']['url']['config']['absoluteUri'] = 1;
        }

        if ($this->{$this->fieldSenderFrom} != '') {
            $conf['resource']['email']['from-name'] = $this->{$this->fieldSenderFrom};
        }

        if ($this->{$this->fieldSenderEmail} != '') {
            $conf['resource']['email']['from-email'] = $this->{$this->fieldSenderEmail};
        }

        if ($this->{$this->fieldReplyEmail} != '') {
            $conf['resource']['email']['reply-email'] = $this->{$this->fieldReplyEmail};
        }

        if ($this->{$this->fieldPageCatalog} != '') {
            $conf['client']['html']['catalog']['lists']['url']['target'] = $this->{$this->fieldPageCatalog};
        }

        if ($this->{$this->fieldPageDetail} != '') {
            $conf['client']['html']['catalog']['detail']['url']['target'] = $this->{$this->fieldPageDetail};
        }

        if ($this->{$this->fieldPageDownload} != '') {
            $conf['client']['html']['account']['download']['url']['target'] = $this->{$this->fieldPageDownload};
        }

        if ($this->{$this->fieldPageLogin} != '') {
            $conf['client']['html']['account']['index']['url']['target'] = $this->{$this->fieldPageLogin};
        }

        $tsconf = Base::parseTS($this->{$this->fieldTSconfig});
        $jobs = (array) $this->{$this->fieldController};

        Scheduler\Base::execute($tsconf, $conf, $jobs, $this->{$this->fieldSite}, $this->{$this->fieldPageDetail});

        return true;
    }
}
