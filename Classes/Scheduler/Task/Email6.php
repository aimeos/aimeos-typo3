<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Scheduler\Task;


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


	/**
	 * Executes the configured tasks.
	 *
	 * @return boolean True if success, false if not
	 * @throws Exception If an error occurs
	 */
	public function execute()
	{
		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->user['lang'] ) && $GLOBALS['BE_USER']->user['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->user['lang'];
		}


		$conf = \Tx_Aimeos_Base::parseTS( $this->{$this->_fieldTSconfig} );

		if( $this->{$this->_fieldSenderFrom} != '' ) {
			$conf['client']['html']['email']['from-name'] = $this->{$this->_fieldSenderFrom};
		}

		if( $this->{$this->_fieldSenderEmail} != '' ) {
			$conf['client']['html']['email']['from-email'] = $this->{$this->_fieldSenderEmail};
		}

		if( $this->{$this->_fieldReplyEmail} != '' ) {
			$conf['client']['html']['email']['reply-email'] = $this->{$this->_fieldReplyEmail};
		}

		if( $this->{$this->_fieldContentBaseurl} != '' ) {
			$conf['client']['html']['common']['content']['baseurl'] = $this->{$this->_fieldContentBaseurl};
		}

		if( $this->{$this->_fieldPageDetail} != '' ) {
			$conf['client']['html']['catalog']['detail']['url']['target'] = $this->{$this->_fieldPageDetail};
		}

		$context = \Tx_Aimeos_Scheduler_Base::getContext( $conf );
		$aimeos = \Tx_Aimeos_Base::getAimeos();

		$manager = \MShop_Locale_Manager_Factory::createManager( $context );

		foreach( (array) $this->{$this->_fieldSite} as $sitecode )
		{
			$localeItem = $manager->bootstrap( $sitecode, $langid, '', false );
			$context->setLocale( $localeItem );

			foreach( (array) $this->{$this->_fieldController} as $name ) {
				\Controller_Jobs_Factory::createController( $context, $aimeos, $name )->run();
			}
		}

		return true;
	}
}
