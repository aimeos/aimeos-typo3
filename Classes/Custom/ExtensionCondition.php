<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;


use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;


/**
 * This TypoScript condition checks whether a extension is installed or not including version compares
 *
 * = Example =
 * [userFunc = \Aimeos\Aimeos\Custom\ExtensionCondition::match(bootstrap_package, greaterOrEqualThan, 7.0.0)]
 *   page.6 = TEXT
 *   page.6.value = LOWER CMS 7
 *   page.6.wrap = <div>|</div>
 * [global]
 *
 */
class ExtensionCondition
{

	/**
	 * @param string $extension
	 * @param string $operator
	 * @param integer $value
	 * @return bool
	 */
	public function match($extension, $operator = null, $value = null)
	{
		$version = ExtensionManagementUtility::getExtensionVersion( $extension );
		$result = !empty( $version );

		if( !empty( $operator ) ) {
			if( !$result && $operator === "notInstalled" ) {
				$result =  true;
			} elseif( $result && !empty( $value ) ) {
				$result = false;
				$version = VersionNumberUtility::convertVersionNumberToInteger( $version );
				$value = VersionNumberUtility::convertVersionNumberToInteger( $value );

				//ExtensionManagementUtility::isLoaded( );
				if ($operator === "equals" && $version === $value) {
					$result = true;
				} elseif ($operator === "lessThan" && $version < $value) {
					$result = true;
				} elseif ($operator === "lessOrEqualThan" && $version <= $value) {
					$result = true;
				} elseif ($operator === "greaterThan" && $version > $value) {
					$result = true;
				} elseif ($operator === "greaterOrEqualThan" && $version >= $value) {
					$result = true;
				}
			} else {
				$result = false;
			}
		}

		return $result;
	}
}
