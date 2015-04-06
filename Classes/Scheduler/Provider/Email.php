<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Scheduler\Provider;


/**
 * Common methods for Aimeos' e-mail additional field providers.
 *
 * @package TYPO3_Aimeos
 */
abstract class Email extends AbstractProvider
{
	private $_fieldSenderFrom = 'aimeos_sender_from';
	private $_fieldSenderEmail = 'aimeos_sender_email';
	private $_fieldReplyEmail = 'aimeos_reply_email';
	private $_fieldPageDetail = 'aimeos_pageid_detail';
	private $_fieldContentBaseurl = 'aimeos_content_baseurl';


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
	protected function _getAdditionalFields( array &$taskInfo, $task, $parentObject )
	{
		$additionalFields = array();


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldSenderFrom] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldSenderFrom] = $task->{$this->_fieldSenderFrom};
		}

		$taskInfo[$this->_fieldSenderFrom] = htmlspecialchars( $taskInfo[$this->_fieldSenderFrom], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->_fieldSenderFrom, $taskInfo[$this->_fieldSenderFrom] );

		$additionalFields[$this->_fieldSenderFrom] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.label.from-name',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldSenderFrom
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldSenderEmail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldSenderEmail] = $task->{$this->_fieldSenderEmail};
		}

		$taskInfo[$this->_fieldSenderEmail] = htmlspecialchars( $taskInfo[$this->_fieldSenderEmail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->_fieldSenderEmail, $taskInfo[$this->_fieldSenderEmail] );

		$additionalFields[$this->_fieldSenderEmail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.label.from-email',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldSenderEmail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldReplyEmail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldReplyEmail] = $task->{$this->_fieldReplyEmail};
		}

		$taskInfo[$this->_fieldReplyEmail] = htmlspecialchars( $taskInfo[$this->_fieldReplyEmail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->_fieldReplyEmail, $taskInfo[$this->_fieldReplyEmail] );

		$additionalFields[$this->_fieldReplyEmail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.label.reply-email',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldReplyEmail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldPageDetail] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldPageDetail] = $task->{$this->_fieldPageDetail};
		}

		$taskInfo[$this->_fieldPageDetail] = htmlspecialchars( $taskInfo[$this->_fieldPageDetail], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->_fieldPageDetail, $taskInfo[$this->_fieldPageDetail] );

		$additionalFields[$this->_fieldPageDetail] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.label.page-detail',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldPageDetail
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldContentBaseurl] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldContentBaseurl] = $task->{$this->_fieldContentBaseurl};
		}

		$taskInfo[$this->_fieldContentBaseurl] = htmlspecialchars( $taskInfo[$this->_fieldContentBaseurl], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<input name="tx_scheduler[%1$s]" id="%1$s" value="%2$s">';
		$fieldCode = sprintf( $fieldStr, $this->_fieldContentBaseurl, $taskInfo[$this->_fieldContentBaseurl] );

		$additionalFields[$this->_fieldContentBaseurl] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.label.content-baseurl',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldContentBaseurl
		);


		$additionalFields += parent::_getAdditionalFields( $taskInfo, $task, $parentObject );

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
	protected function _saveAdditionalFields( array $submittedData, $task )
	{
		parent::_saveAdditionalFields( $submittedData, $task );

		$task->{$this->_fieldSenderFrom} = $submittedData[$this->_fieldSenderFrom];
		$task->{$this->_fieldSenderEmail} = $submittedData[$this->_fieldSenderEmail];
		$task->{$this->_fieldReplyEmail} = $submittedData[$this->_fieldReplyEmail];
		$task->{$this->_fieldPageDetail} = $submittedData[$this->_fieldPageDetail];
		$task->{$this->_fieldContentBaseurl} = $submittedData[$this->_fieldContentBaseurl];
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
	protected function _validateAdditionalFields( array &$submittedData, $parentObject )
	{
		if( preg_match( '/^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/', $submittedData[$this->_fieldSenderEmail] ) !== 1 ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.error.from-email.invalid' ) );
		}

		if( $submittedData[$this->_fieldReplyEmail] != ''
			&& preg_match( '/^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/', $submittedData[$this->_fieldReplyEmail] ) !== 1
		) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.error.reply-email.invalid' ) );
		}

		if( $submittedData[$this->_fieldPageDetail] != ''
			&& preg_match( '/^[0-9]+$/', $submittedData[$this->_fieldPageDetail] ) !== 1 ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.error.page-detail.invalid' ) );
		}

		if( $submittedData[$this->_fieldContentBaseurl] != ''
			&& preg_match( '#^[a-z]+://[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(:[0-9]+)?/.*$#', $submittedData[$this->_fieldContentBaseurl] ) !== 1 ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:email.error.content-baseurl.invalid' ) );
		}

		parent::_validateAdditionalFields( $submittedData, $parentObject );

		return true;
	}


	/**
	 * Returns the HTML code for the controller control.
	 *
	 * @param array $selected List of site codes that were previously selected by the user
	 * @return string HTML code with <option> tags for the select box
	 */
	protected function _getControllerOptions( array $selected )
	{
		$html = '';
		$aimeos = \Aimeos\Aimeos\Base::getAimeos();
		$context = \Aimeos\Aimeos\Scheduler\Base::getContext();
		$cntlPaths = $aimeos->getCustomPaths( 'controller/jobs' );

		$controllers = \Controller_Jobs_Factory::getControllers( $context, $aimeos, $cntlPaths );

		foreach( $controllers as $name => $controller )
		{
			if( strstr( $name, 'email' ) !== false )
			{
				$active = ( in_array( $name, $selected ) ? 'selected="selected"' : '' );
				$title = htmlspecialchars( $controller->getDescription(), ENT_QUOTES, 'UTF-8' );
				$cntl = htmlspecialchars( $controller->getName(), ENT_QUOTES, 'UTF-8' );
				$name = htmlspecialchars( $name, ENT_QUOTES, 'UTF-8' );

				$html .= sprintf( '<option value="%1$s" title="%2$s" %3$s>%4$s</option>', $name, $title, $active, $cntl );
			}
		}

		return $html;
	}
}
