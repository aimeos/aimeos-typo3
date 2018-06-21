<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Scheduler\Provider;

use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Common methods for Aimeos' e-mail additional field providers.
 *
 * @package TYPO3
 */
abstract class Email extends AbstractProvider
{
	private $fieldSenderFrom = 'aimeos_sender_from';
	private $fieldSenderEmail = 'aimeos_sender_email';
	private $fieldReplyEmail = 'aimeos_reply_email';
	private $fieldPageDetail = 'aimeos_pageid_detail';
	private $fieldPageDownload = 'aimeos_pageid_download';
	private $fieldContentBaseurl = 'aimeos_content_baseurl';
	private $fieldTemplateBaseurl = 'aimeos_template_baseurl';


	/**
	 * Fields generation.
	 * This method is used to define new fields for adding or editing a task
	 * In this case, it adds a page ID field
	 *
	 * @param array $taskInfo Reference to the array containing the info used in the add/edit form
	 * @param object $task When editing, reference to the current task object. Null when adding.
	 * @param object $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return array Array containg all the information pertaining to the additional fields
	 *		The array is multidimensional, keyed to the task class name and each field's id
	 *		For each field it provides an associative sub-array with the following:
	 *			['code']		=> The HTML code for the field
	 *			['label']		=> The label of the field (possibly localized)
	 *			['cshKey']		=> The CSH key for the field
	 *			['cshLabel']	=> The code of the CSH label
	 */
	protected function getFields( array &$taskInfo, $task, $parentObject )
	{
		$additionalFields = array();


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldSenderFrom] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldSenderFrom] = $task->{$this->fieldSenderFrom};
		}

		$taskInfo[$this->fieldSenderFrom] = htmlspecialchars( $taskInfo[$this->fieldSenderFrom], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldSenderFrom, $taskInfo[$this->fieldSenderFrom] );

		$additionalFields[$this->fieldSenderFrom] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.from-name',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldSenderFrom
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldSenderEmail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldSenderEmail] = $task->{$this->fieldSenderEmail};
		}

		$taskInfo[$this->fieldSenderEmail] = htmlspecialchars( $taskInfo[$this->fieldSenderEmail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldSenderEmail, $taskInfo[$this->fieldSenderEmail] );

		$additionalFields[$this->fieldSenderEmail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.from-email',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldSenderEmail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldReplyEmail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldReplyEmail] = $task->{$this->fieldReplyEmail};
		}

		$taskInfo[$this->fieldReplyEmail] = htmlspecialchars( $taskInfo[$this->fieldReplyEmail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldReplyEmail, $taskInfo[$this->fieldReplyEmail] );

		$additionalFields[$this->fieldReplyEmail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.reply-email',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldReplyEmail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldPageDetail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldPageDetail] = $task->{$this->fieldPageDetail};
		}

		$taskInfo[$this->fieldPageDetail] = htmlspecialchars( $taskInfo[$this->fieldPageDetail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldPageDetail, $taskInfo[$this->fieldPageDetail] );

		$additionalFields[$this->fieldPageDetail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.page-detail',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldPageDetail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldPageDownload] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldPageDownload] = $task->{$this->fieldPageDownload};
		}

		$taskInfo[$this->fieldPageDownload] = htmlspecialchars( $taskInfo[$this->fieldPageDownload], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldPageDownload, $taskInfo[$this->fieldPageDownload] );

		$additionalFields[$this->fieldPageDownload] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.page-download',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldPageDownload
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldContentBaseurl] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldContentBaseurl] = $task->{$this->fieldContentBaseurl};
		}

		$taskInfo[$this->fieldContentBaseurl] = htmlspecialchars( $taskInfo[$this->fieldContentBaseurl], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldContentBaseurl, $taskInfo[$this->fieldContentBaseurl] );

		$additionalFields[$this->fieldContentBaseurl] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.content-baseurl',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldContentBaseurl
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldTemplateBaseurl] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldTemplateBaseurl] = $task->{$this->fieldTemplateBaseurl};
		}

		$taskInfo[$this->fieldTemplateBaseurl] = htmlspecialchars( $taskInfo[$this->fieldTemplateBaseurl], ENT_QUOTES, 'UTF-8' );

		if( $taskInfo[$this->fieldTemplateBaseurl] == '' ) {
			$taskInfo[$this->fieldTemplateBaseurl] = 'typo3conf/ext/aimeos/Resources/Public/Themes/elegance';
		}

		$path = 'typo3conf/ext/aimeos/Resources/Public/Themes/elegance';
		$path = ( $taskInfo[$this->fieldTemplateBaseurl] !== '' ? $taskInfo[$this->fieldTemplateBaseurl] : $path );

		$fieldStr = '<input class="form-control" name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->fieldTemplateBaseurl, $path );

		$additionalFields[$this->fieldTemplateBaseurl] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.label.template-baseurl',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldTemplateBaseurl
		);


		$additionalFields += parent::getFields( $taskInfo, $task, $parentObject );

		return $additionalFields;
	}


	/**
	 * Store fields.
	 * This method is used to save any additional input into the current task object
	 * if the task class matches
	 *
	 * @param array $submittedData Array containing the data submitted by the user
	 * @param object $task Reference to the current task object
	 */
	protected function saveFields( array $submittedData, $task )
	{
		parent::saveFields( $submittedData, $task );

		$task->{$this->fieldSenderFrom} = $submittedData[$this->fieldSenderFrom];
		$task->{$this->fieldSenderEmail} = $submittedData[$this->fieldSenderEmail];
		$task->{$this->fieldReplyEmail} = $submittedData[$this->fieldReplyEmail];
		$task->{$this->fieldPageDetail} = $submittedData[$this->fieldPageDetail];
		$task->{$this->fieldPageDownload} = $submittedData[$this->fieldPageDownload];
		$task->{$this->fieldContentBaseurl} = $submittedData[$this->fieldContentBaseurl];
		$task->{$this->fieldTemplateBaseurl} = $submittedData[$this->fieldTemplateBaseurl];
	}


	/**
	 * Fields validation.
	 * This method checks if page id given in the 'Hide content' specific task is int+
	 * If the task class is not relevant, the method is expected to return true
	 *
	 * @param array $submittedData Reference to the array containing the data submitted by the user
	 * @param tx_scheduler_Module $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return boolean True if validation was ok (or selected class is not relevant), false otherwise
	 */
	protected function validateFields( array &$submittedData, $parentObject )
	{
		if( preg_match( '/^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/', $submittedData[$this->fieldSenderEmail] ) !== 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.error.from-email.invalid' ) );
		}

		if( $submittedData[$this->fieldReplyEmail] != '' && preg_match( '/^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/', $submittedData[$this->fieldReplyEmail] ) !== 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.error.reply-email.invalid' ) );
		}

		if( preg_match( '/^[0-9]+$/', $submittedData[$this->fieldPageDetail] ) !== 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.error.page-detail.invalid' ) );
		}

		if( preg_match( '/^[0-9]+$/', $submittedData[$this->fieldPageDownload] ) !== 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.error.page-download.invalid' ) );
		}

		if( preg_match( '#^[a-z]+://[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(:[0-9]+)?(/.*)?$#', $submittedData[$this->fieldContentBaseurl] ) !== 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.error.content-baseurl.invalid' ) );
		}

		parent::validateFields( $submittedData, $parentObject );

		return true;
	}
}
