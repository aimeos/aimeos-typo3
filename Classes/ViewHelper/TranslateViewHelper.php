<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\ViewHelper;


use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;


class TranslateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	protected $escapeOutput = false;


	public function initializeArguments()
	{
		$this->registerArgument( 'singular', 'string', 'Singular form of the text to translate' );
		$this->registerArgument( 'plural', 'string', 'Plural form of the text, used if $number is greater than one', false );
		$this->registerArgument( 'number', 'integer', 'Amount of things relevant for the plural form', false );
		$this->registerArgument( 'escape', 'boolean', 'If translated string should be escaped', false );
		$this->registerArgument( 'domain', 'string', 'Translation domain from core or an extension', false );
		$this->registerArgument( 'arguments', 'array', 'Arguments to be replaced in the resulting string', false );
	}


	public function render()
	{
		$iface = '\Aimeos\MW\View\Iface';
		$view = $this->templateVariableContainer->get( '_aimeos_view' );

		if( !is_object( $view ) || !( $view instanceof $iface ) ) {
			throw new Exception( 'Aimeos view object is missing' );
		}

		if( !isset( $this->arguments['singular'] ) ) {
			throw new Exception( 'Attribute "singular" missing for Aimeos translate view helper' );
		}

		$singular = $this->arguments['singular'];
		$plural = ( isset( $this->arguments['plural'] ) ? $this->arguments['plural'] : '' );
		$number = ( isset( $this->arguments['number'] ) ? $this->arguments['number'] : 1 );
		$escape = ( isset( $this->arguments['escape'] ) ? $this->arguments['escape'] : true );
		$domain = ( isset( $this->arguments['domain'] ) ? $this->arguments['domain'] : 'client' );
		$values = ( isset( $this->arguments['arguments'] ) ? $this->arguments['arguments'] : array() );

		$string = vsprintf( $view->translate( $domain, $singular, $plural, $number ), (array) $values );

		if( $escape === false ) {
			return $string;
		}

		return $view->encoder()->html( $string );
	}
}