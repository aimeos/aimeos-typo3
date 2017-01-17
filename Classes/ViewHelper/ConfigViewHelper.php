<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\ViewHelper;


use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;


class ConfigViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	protected $escapeOutput = false;


	public function initializeArguments()
	{
		$this->registerArgument( 'key', 'string', 'Configuration key, e.g. client/html/catalog/lists/basket-add' );
		$this->registerArgument( 'default', 'mixed', 'Value if no configuration for the given key was found', false );
	}


	public function render()
	{
		$iface = '\Aimeos\MW\View\Iface';
		$view = $this->templateVariableContainer->get( '_aimeos_view' );

		if( !is_object( $view ) || !( $view instanceof $iface ) ) {
			throw new Exception( 'Aimeos view object is missing' );
		}

		if( !isset( $this->arguments['key'] ) ) {
			throw new Exception( 'Attribute "key" missing for Aimeos translate view helper' );
		}

		$key = $this->arguments['key'];
		$default = ( isset( $this->arguments['default'] ) ? $this->arguments['default'] : null );

		return $view->config( $key, $default );
	}
}