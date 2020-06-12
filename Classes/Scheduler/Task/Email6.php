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
	private $fieldSite = 'aimeos_sitecode';
	private $fieldTSconfig = 'aimeos_config';
	private $fieldController = 'aimeos_controller';
	private $fieldSenderFrom = 'aimeos_sender_from';
	private $fieldSenderEmail = 'aimeos_sender_email';
	private $fieldReplyEmail = 'aimeos_reply_email';
	private $fieldPageLogin = 'aimeos_pageid_login';
	private $fieldPageDetail = 'aimeos_pageid_detail';
	private $fieldPageDownload = 'aimeos_pageid_download';
	private $fieldTemplateBaseurl = 'aimeos_template_baseurl';


	/**
	 * Executes the configured tasks.
	 *
	 * @return bool True if success
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$conf = Base::parseTS( $this->{$this->fieldTSconfig} );

		if( !isset( $conf['client']['html']['catalog']['detail']['url']['config']['absoluteUri'] ) ) {
			$conf['client']['html']['catalog']['detail']['url']['config']['absoluteUri'] = 1;
		}

		if( !isset( $conf['client']['html']['account']['download']['url']['config']['absoluteUri'] ) ) {
			$conf['client']['html']['account']['download']['url']['config']['absoluteUri'] = 1;
		}

		if( $this->{$this->fieldSenderFrom} != '' ) {
			$conf['client']['html']['email']['from-name'] = $this->{$this->fieldSenderFrom};
		}

		if( $this->{$this->fieldSenderEmail} != '' ) {
			$conf['client']['html']['email']['from-email'] = $this->{$this->fieldSenderEmail};
		}

		if( $this->{$this->fieldReplyEmail} != '' ) {
			$conf['client']['html']['email']['reply-email'] = $this->{$this->fieldReplyEmail};
		}

		if( $this->{$this->fieldPageDetail} != '' ) {
			$conf['client']['html']['catalog']['detail']['url']['target'] = $this->{$this->fieldPageDetail};
		}

		if( $this->{$this->fieldPageDownload} != '' ) {
			$conf['client']['html']['account']['download']['url']['target'] = $this->{$this->fieldPageDownload};
		}

		if( $this->{$this->fieldPageLogin} != '' ) {
			$conf['client']['html']['account']['index']['url']['target'] = $this->{$this->fieldPageLogin};
		}

		if( $this->{$this->fieldTemplateBaseurl} != '' )
		{
			$themeDir = $this->{$this->fieldTemplateBaseurl};

			if( $themeDir[0] !== '/' ) {
				$themeDir = realpath( \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/' . $themeDir );
			}

			$conf['client']['html']['common']['template']['baseurl'] = $themeDir;
		}

		$jobs = (array) $this->{$this->fieldController};
		Scheduler\Base::execute( $conf, $jobs, $this->{$this->fieldSite}, $this->{$this->fieldPageDetail} );

		return true;
	}
}
