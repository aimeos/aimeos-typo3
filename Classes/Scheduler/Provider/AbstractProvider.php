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
 * Common methods for Aimeos' additional field providers.
 *
 * @package TYPO3
 */
abstract class AbstractProvider
{
	private $fieldSite = 'aimeos_sitecode';
	private $fieldController = 'aimeos_controller';
	private $fieldTSconfig = 'aimeos_config';


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
		if( empty( $taskInfo[$this->fieldController] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldController] = $task->{$this->fieldController};
		}

		$taskInfo[$this->fieldController] = (array) $taskInfo[$this->fieldController];

		$fieldCode = sprintf( '<select class="form-control" name="tx_scheduler[%1$s][]" id="%1$s" multiple="multiple" size="10" />', $this->fieldController );
		$fieldCode .= $this->getControllerOptions( $taskInfo[$this->fieldController] );
		$fieldCode .= '</select>';

		$additionalFields[$this->fieldController] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.controller',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldController
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldSite] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldSite] = $task->{$this->fieldSite};
		}

		$taskInfo[$this->fieldSite] = (array) $taskInfo[$this->fieldSite];

		$fieldCode = sprintf( '<select class="form-control" name="tx_scheduler[%1$s][]" id="%1$s" multiple="multiple" size="10" />', $this->fieldSite );
		$fieldCode .= $this->getSiteOptions( $this->getAvailableSites(), $taskInfo[$this->fieldSite], 0 );
		$fieldCode .= '</select>';

		$additionalFields[$this->fieldSite] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.sitecode',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldSite
		);


		// In case of editing a task, set to the internal value if data wasn't already submitted
		if( empty( $taskInfo[$this->fieldTSconfig] ) && $parentObject->CMD === 'edit' ) {
			$taskInfo[$this->fieldTSconfig] = $task->{$this->fieldTSconfig};
		}

		$taskInfo[$this->fieldTSconfig] = htmlspecialchars( $taskInfo[$this->fieldTSconfig], ENT_QUOTES, 'UTF-8' );

		$fieldStr = '<textarea class="form-control" name="tx_scheduler[%1$s]" id="%1$s" rows="20" cols="80" >%2$s</textarea>';
		$fieldCode = sprintf( $fieldStr, $this->fieldTSconfig, $taskInfo[$this->fieldTSconfig] );

		$additionalFields[$this->fieldTSconfig] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.label.tsconfig',
			'cshKey'   => 'xMOD_tx_aimeos',
			'cshLabel' => $this->fieldTSconfig
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
	protected function saveFields( array $submittedData, $task )
	{
		$task->{$this->fieldSite} = $submittedData[$this->fieldSite];
		$task->{$this->fieldController} = $submittedData[$this->fieldController];
		$task->{$this->fieldTSconfig} = $submittedData[$this->fieldTSconfig];
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
		if( count( (array) $submittedData[$this->fieldController] ) < 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.error.controller.missing' ) );
		}

		if( count( (array) $submittedData[$this->fieldSite] ) < 1 ) {
			throw new \InvalidArgumentException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.error.sitecode.missing' ) );
		}

		Base::parseTS( $submittedData[$this->fieldTSconfig] );


		$context = Scheduler\Base::getContext();
		$submittedData[$this->fieldSite] = array_unique( (array) $submittedData[$this->fieldSite] );
		$siteItems = Scheduler\Base::getSiteItems( $context, $submittedData[$this->fieldSite] );

		if( count( $siteItems ) !== count( $submittedData[$this->fieldSite] ) ) {
			throw new \RuntimeException( $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.error.sitecode' ) );
		}


		$aimeos = Base::getAimeos();
		$cntlPaths = $aimeos->getCustomPaths( 'controller/jobs' );
		$submittedData[$this->fieldController] = array_unique( (array) $submittedData[$this->fieldController] );

		foreach( $submittedData[$this->fieldController] as $name ) {
			\Aimeos\Controller\Jobs\Factory::createController( $context, $aimeos, $name );
		}

		return true;
	}


	/**
	 * Returns the list of site trees.
	 *
	 * @return array Associative list of items and children implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 */
	protected function getAvailableSites()
	{
		$manager = \Aimeos\MShop\Factory::createManager( Scheduler\Base::getContext(), 'locale/site' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'locale.site.level', 0 ) );
		$search->setSortations( array( $search->sort( '+', 'locale.site.label' ) ) );

		$sites = $manager->searchItems( $search );

		foreach( $sites as $id => $siteItem ) {
			$sites[$id] = $manager->getTree( $id, array(), \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE );
		}

		return $sites;
	}


	/**
	 * Returns the HTML code for the select control.
	 * The method adds every site and its children recursively.
	 *
	 * @param array $siteItems List of items implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 * @param array $selected List of site codes that were previously selected by the user
	 * @param integer $level Nesting level of the sites (should start with 0)
	 * @return string HTML code with <option> tags for the select box
	 */
	protected function getSiteOptions( array $siteItems, array $selected, $level )
	{
		$html = '';
		$prefix = str_repeat( '-', $level ) . ' ';

		foreach( $siteItems as $item )
		{
			$active = ( in_array( $item->getCode(), $selected ) ? 'selected="selected"' : '' );
			$disabled = ( $item->getStatus() > 0 ? '' : 'disabled="disabled"' );
			$string = '<option value="%1$s" %2$s %3$s>%4$s</option>';
			$html .= sprintf( $string, $item->getCode(), $active, $disabled, $prefix . $item->getLabel() );

			$html .= $this->getSiteOptions( $item->getChildren(), $selected, $level+1 );
		}

		return $html;
	}


	/**
	 * Returns the HTML code for the controller control.
	 *
	 * @param array $selected List of site codes that were previously selected by the user
	 * @return string HTML code with <option> tags for the select box
	 */
	protected function getControllerOptions( array $selected )
	{
		$html = '';
		$aimeos = Base::getAimeos();
		$context = Scheduler\Base::getContext();
		$cntlPaths = $aimeos->getCustomPaths( 'controller/jobs' );

		$langid = 'en';
		if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
			$langid = $GLOBALS['BE_USER']->uc['lang'];
		}

		$localeItem = \Aimeos\MShop\Factory::createManager( $context, 'locale' )->createItem();
		$localeItem->setLanguageId( $langid );
		$context->setLocale( $localeItem );

		$controllers = \Aimeos\Controller\Jobs\Factory::getControllers( $context, $aimeos, $cntlPaths );

		foreach( $controllers as $name => $controller )
		{
			$active = ( in_array( $name, $selected ) ? 'selected="selected"' : '' );
			$title = htmlspecialchars( $controller->getDescription(), ENT_QUOTES, 'UTF-8' );
			$cntl = htmlspecialchars( $controller->getName(), ENT_QUOTES, 'UTF-8' );
			$name = htmlspecialchars( $name, ENT_QUOTES, 'UTF-8' );

			$html .= sprintf( '<option value="%1$s" title="%2$s" %3$s>%4$s</option>', $name, $title, $active, $cntl );
		}

		return $html;
	}
}
