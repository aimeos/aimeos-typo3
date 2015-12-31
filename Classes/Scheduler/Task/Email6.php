<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2015
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Scheduler\Task;

use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Aimeos e-mail scheduler.
 *
 * @package TYPO3_Aimeos
 */
class Email6 extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
	private $fieldSite = 'aimeos_sitecode';
	private $fieldController = 'aimeos_controller';
	private $fieldTSconfig = 'aimeos_config';
	private $fieldSenderFrom = 'aimeos_sender_from';
	private $fieldSenderEmail = 'aimeos_sender_email';
	private $fieldReplyEmail = 'aimeos_reply_email';
	private $fieldPageDetail = 'aimeos_pageid_detail';
	private $fieldContentBaseurl = 'aimeos_content_baseurl';
	private $fieldTemplateBaseurl = 'aimeos_template_baseurl';


	/**
	 * Executes the configured tasks.
	 *
	 * @return boolean True if success
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$conf = Base::parseTS( $this->{$this->fieldTSconfig} );

		if( !isset( $conf['client']['html']['catalog']['detail']['url']['config'] ) ) {
			$conf['client']['html']['catalog']['detail']['url']['config'] = array(
				'plugin' => 'catalog-detail',
				'extension' => 'aimeos',
				'absoluteUri' => 1,
			);
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

		if( $this->{$this->fieldContentBaseurl} != '' ) {
			$conf['client']['html']['common']['content']['baseurl'] = $this->{$this->fieldContentBaseurl};
		}

		if( $this->{$this->fieldTemplateBaseurl} != '' )
		{
			$themeDir = $this->{$this->fieldTemplateBaseurl};

			if( $themeDir[0] !== '/' ) {
				$themeDir = realpath( PATH_site . $themeDir );
			}

			$conf['client']['html']['common']['template']['baseurl'] = $themeDir;
		}

		Scheduler\Base::initFrontend( $this->{$this->fieldPageDetail} );
		Scheduler\Base::execute( $conf, (array) $this->{$this->fieldController}, $this->{$this->fieldSite} );

		return true;
	}
}
