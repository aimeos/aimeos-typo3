<?php

// Register necessary classes with autoloader
$extensionPath = t3lib_extMgm::extPath( 'aimeos' );

return array(
	'tx_aimeos_base' => $extensionPath . 'Classes/Base.php',
	'tx_aimeos_setup' => $extensionPath . 'Classes/Setup.php',

	'tx_aimeos_custom_realurl' => $extensionPath . 'Classes/Custom/Realurl.php',
	'tx_aimeos_custom_wizicon' => $extensionPath . 'Classes/Custom/Wizicon.php',

	'tx_aimeos_flexform_abstract' => $extensionPath . 'Classes/Flexform/Abstract.php',
	'tx_aimeos_flexform_catalog' => $extensionPath . 'Classes/Flexform/Catalog.php',

	'tx_aimeos_scheduler_base' => $extensionPath . 'Classes/Scheduler/Base.php',
	'tx_aimeos_scheduler_task_typo4' => $extensionPath . 'Classes/Scheduler/Task/Typo4.php',
	'tx_aimeos_scheduler_provider_typo4' => $extensionPath . 'Classes/Scheduler/Provider/Typo4.php',
	'tx_aimeos_scheduler_provider_abstract' => $extensionPath . 'Classes/Scheduler/Provider/Abstract.php',
);

?>
