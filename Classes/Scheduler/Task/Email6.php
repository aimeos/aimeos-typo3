<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
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
	private $_fieldSite = 'aimeos_sitecode';
	private $_fieldController = 'aimeos_controller';
	private $_fieldTSconfig = 'aimeos_config';
	private $_fieldSenderFrom = 'aimeos_sender_from';
	private $_fieldSenderEmail = 'aimeos_sender_email';
	private $_fieldReplyEmail = 'aimeos_reply_email';
	private $_fieldPageDetail = 'aimeos_pageid_detail';
	private $_fieldContentBaseurl = 'aimeos_content_baseurl';
	private $_fieldTemplateBaseurl = 'aimeos_template_baseurl';


	/**
	 * Executes the configured tasks.
	 *
	 * @return boolean True if success
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$conf = Base::parseTS( $this->{$this->_fieldTSconfig} );

		if( !isset( $conf['client']['html']['catalog']['detail']['url']['config'] ) ) {
			$conf['client']['html']['catalog']['detail']['url']['config'] = array(
				'plugin' => 'catalog-detail',
				'extension' => 'aimeos',
				'absoluteUri' => 1,
			);
		}

		if( $this->{$this->_fieldSenderFrom} != '' ) {
			$conf['client']['html']['email']['from-name'] = $this->{$this->_fieldSenderFrom};
		}

		if( $this->{$this->_fieldSenderEmail} != '' ) {
			$conf['client']['html']['email']['from-email'] = $this->{$this->_fieldSenderEmail};
		}

		if( $this->{$this->_fieldReplyEmail} != '' ) {
			$conf['client']['html']['email']['reply-email'] = $this->{$this->_fieldReplyEmail};
		}

		if( $this->{$this->_fieldPageDetail} != '' ) {
			$conf['client']['html']['catalog']['detail']['url']['target'] = $this->{$this->_fieldPageDetail};
		}

		if( $this->{$this->_fieldContentBaseurl} != '' ) {
			$conf['client']['html']['common']['content']['baseurl'] = $this->{$this->_fieldContentBaseurl};
		}

		if( $this->{$this->_fieldTemplateBaseurl} != '' )
		{
			$themeDir = $this->{$this->_fieldTemplateBaseurl};

			if( $themeDir != '' && $themeDir[0] !== '/' ) {
				realpath( PATH_site . $themeDir );
			}

			$conf['client']['html']['common']['template']['baseurl'] = $this->{$this->_fieldTemplateBaseurl};
		}

		Scheduler\Base::initFrontend( $this->{$this->_fieldPageDetail} );

		$context = Scheduler\Base::getContext( $conf );

		Scheduler\Base::execute( $context, $this->{$this->_fieldController}, $this->{$this->_fieldSite} );

		return true;
	}
}
