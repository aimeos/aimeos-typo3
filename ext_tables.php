<?php

defined('TYPO3') or die();


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('aimeos');

if (file_exists($aimeosExtPath . '/Resources/Libraries/autoload.php') === true) {
    require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}

?>