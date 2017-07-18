<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Wizard\NewContentElementWizardHookInterface;


class WizardItem implements NewContentElementWizardHookInterface
{
	public function manipulateWizardItems( &$wizardItems, &$parentObject )
	{
		if( isset( $wizardItems['plugins_tx_aimeos'] ) && isset( $wizardItems['plugins_tx_aimeos']['icon'] ) )
		{
			$iconRegistry = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Imaging\\IconRegistry' );
			$iconRegistry->registerIcon(
				'tx-aimeos-wizicon',
				'TYPO3\\CMS\\Core\\Imaging\\IconProvider\\SvgIconProvider',
				array( 'source' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg' )
			);

			$wizardItems['plugins_tx_aimeos']['iconIdentifier'] = 'tx-aimeos-wizicon';
		}
	}
}
