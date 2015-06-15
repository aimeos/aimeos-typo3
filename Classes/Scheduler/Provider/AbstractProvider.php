<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */

namespace Aimeos\Aimeos\Scheduler\Provider;


use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Common methods for Aimeos' additional field providers.
 *
 * @package TYPO3_Aimeos
 */
abstract class AbstractProvider
{
	private $_fieldSite = 'aimeos_sitecode';
	private $_fieldController = 'aimeos_controller';
	private $_fieldTSconfig = 'aimeos_config';


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
		if( empty( $taskInfo[$this->_fieldController] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldController] = $task->{$this->_fieldController};
		}

		$taskInfo[$this->_fieldController] = (array) $taskInfo[$this->_fieldController];

		$fieldCode = sprintf( '<select class="form-control" name="tx_scheduler[%1$s][]" id="%1$s" multiple="multiple" size="10" />', $this->_fieldController );
		$fieldCode .= $this->_getControllerOptions( $taskInfo[$this->_fieldController], $this->_getJobFilter() );
		$fieldCode .= '</select>';

		$additionalFields[$this->_fieldController] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.controller',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldController
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldSite] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldSite] = $task->{$this->_fieldSite};
		}

		$taskInfo[$this->_fieldSite] = (array) $taskInfo[$this->_fieldSite];

		$fieldCode = sprintf( '<select class="form-control" name="tx_scheduler[%1$s][]" id="%1$s" multiple="multiple" size="10" />', $this->_fieldSite );
		$fieldCode .= $this->_getSiteOptions( $this->_getAvailableSites(), $taskInfo[$this->_fieldSite], 0 );
		$fieldCode .= '</select>';

		$additionalFields[$this->_fieldSite] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.sitecode',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldSite
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->_fieldTSconfig] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->_fieldTSconfig] = $task->{$this->_fieldTSconfig};
		}

		$taskInfo[$this->_fieldTSconfig] = htmlspecialchars( $taskInfo[$this->_fieldTSconfig], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<textarea class="form-control" name="tx_scheduler[%1$s]" id="%1$s" rows="20" cols="80" >%2$s</textarea>';
		$fieldCode = sprintf( $fieldStr, $this->_fieldTSconfig, $taskInfo[$this->_fieldTSconfig] );

		$additionalFields[$this->_fieldTSconfig] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.tsconfig',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->_fieldTSconfig
		);

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
		$task->{$this->_fieldSite} = $submittedData[$this->_fieldSite];
		$task->{$this->_fieldController} = $submittedData[$this->_fieldController];
		$task->{$this->_fieldTSconfig} = $submittedData[$this->_fieldTSconfig];
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
		if( count( (array) $submittedData[$this->_fieldController] ) < 1 ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:default.error.controller.missing' ) );
		}

		if( count( (array) $submittedData[$this->_fieldSite] ) < 1 ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:default.error.sitecode.missing' ) );
		}

		Base::parseTS( $submittedData[$this->_fieldTSconfig] );


		$context = Scheduler\Base::getContext();
		$siteItems = Scheduler\Base::getSiteItems( $context, $submittedData[$this->_fieldSite] );

		if( count( $siteItems ) !== count( $submittedData[$this->_fieldSite] ) ) {
			throw new \Exception( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:default.error.sitecode' ) );
		}


		$aimeos = Base::getAimeos();
		$cntlPaths = $aimeos->getCustomPaths( 'controller/jobs' );

		foreach( (array) $submittedData[$this->_fieldController] as $name ) {
			\Controller_Jobs_Factory::createController( $context, $aimeos, $name );
		}

		return true;
	}


	/**
	 * Returns the list of site trees.
	 *
	 * @return array Associative list of items and children implementing MShop_Locale_Item_Site_Interface
	 */
	protected function _getAvailableSites()
	{
		$manager = \MShop_Factory::createManager( Scheduler\Base::getContext(), 'locale/site' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'locale.site.level', 0 ) );
		$search->setSortations( array( $search->sort( '+', 'locale.site.label' ) ) );

		$sites = $manager->searchItems( $search );

		foreach( $sites as $id => $siteItem ) {
			$sites[$id] = $manager->getTree( $id, array(), \MW_Tree_Manager_Abstract::LEVEL_TREE );
		}

		return $sites;
	}


	/**
	 * Returns the string that must be part of the controller names
	 *
	 * @return string|null Controller name part
	 */
	protected function _getJobFilter()
	{
		return null;
	}


	/**
	 * Returns the HTML code for the select control.
	 * The method adds every site and its children recursively.
	 *
	 * @param array $siteItems List of items implementing MShop_Locale_Item_Site_Interface
	 * @param array $selected List of site codes that were previously selected by the user
	 * @param integer $level Nesting level of the sites (should start with 0)
	 * @return string HTML code with <option> tags for the select box
	 */
	protected function _getSiteOptions( array $siteItems, array $selected, $level )
	{
		$html = '';
		$prefix = str_repeat( '-', $level ) . ' ';

		foreach( $siteItems as $item )
		{
			$active = ( in_array( $item->getCode(), $selected ) ? 'selected="selected"' : '' );
			$disabled = ( $item->getStatus() > 0 ? '' : 'disabled="disabled"' );
			$string = '<option value="%1$s" %2$s %3$s>%4$s</option>';
			$html .= sprintf( $string, $item->getCode(), $active, $disabled, $prefix . $item->getLabel() );

			$html .= $this->_getSiteOptions( $item->getChildren(), $selected, $level+1 );
		}

		return $html;
	}


	/**
	 * Returns the HTML code for the controller control.
	 *
	 * @param array $selected List of site codes that were previously selected by the user
	 * @param string|null $filter String that must be part of the controller name
	 * @return string HTML code with <option> tags for the select box
	 */
	protected function _getControllerOptions( array $selected, $filter = null )
	{
		$html = '';
		$aimeos = Base::getAimeos();
		$context = Scheduler\Base::getContext();
		$cntlPaths = $aimeos->getCustomPaths( 'controller/jobs' );

		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->uc['lang'];
		}

		$localeItem = \MShop_Factory::createManager( $context, 'locale' )->createItem();
		$localeItem->setLanguageId( $langid );
		$context->setLocale( $localeItem );

		$controllers = \Controller_Jobs_Factory::getControllers( $context, $aimeos, $cntlPaths );

		foreach( $controllers as $name => $controller )
		{
			if( $filter !== null && strstr( $name, $filter ) === false ) {
				continue;
			}

			$active = ( in_array( $name, $selected ) ? 'selected="selected"' : '' );
			$title = htmlspecialchars( $controller->getDescription(), ENT_QUOTES, 'UTF-8' );
			$cntl = htmlspecialchars( $controller->getName(), ENT_QUOTES, 'UTF-8' );
			$name = htmlspecialchars( $name, ENT_QUOTES, 'UTF-8' );

			$html .= sprintf( '<option value="%1$s" title="%2$s" %3$s>%4$s</option>', $name, $title, $active, $cntl );
		}

		return $html;
	}
}
