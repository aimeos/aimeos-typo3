<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\ViewHelper;


use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;


class BlockViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	protected $escapeOutput = false;


	public function initializeArguments()
	{
		$this->registerArgument( 'name', 'string', 'Name of the content block' );
	}


	public function render()
	{
		$iface = '\Aimeos\MW\View\Iface';
		$view = $this->templateVariableContainer->get( '_aimeos_view' );

		if( !is_object( $view ) || !( $view instanceof $iface ) ) {
			throw new Exception( 'Aimeos view object is missing' );
		}

		if( !isset( $this->arguments['name'] ) ) {
			throw new Exception( 'Attribute "name" missing for Aimeos translate view helper' );
		}

		$view->block()->set( $this->arguments['name'], $this->renderChildren() );
	}
}