<?php

########################################################################
# Extension Manager/Repository config file for ext: "aimeos"
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Aimeos web shop',
	'description' => 'Aimeos is a fast, flexible and usability optimized shop system and e-commerce solution available in multiple languages (also available as TYPO3 distribution). Several plugins e.g. for facetted search, product listing, detail view, basket, checkout process and other parts of a shop are available. Furthermore, Aimeos offers powerful interfaces to integrate CRM and ERP systems as well as payment and delivery service providers',
	'category' => 'plugin',
	'version' => '17.1.0',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'author' => 'Aimeos',
	'author_email' => 'aimeos@aimeos.org',
	'author_company' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.4.0-7.99.99',
			'typo3' => '6.2.0-7.99.99',
			'fluid' => '6.2.0-7.99.99',
			'extbase' => '6.2.0-7.99.99',
			'scheduler' => '6.2.0-7.99.99',
			'static_info_tables' => '6.0.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'realurl' => '1.12.8-2.99.99',
		),
	),
	'autoload' => array (
		'psr-4' => array (
			'Aimeos\\Aimeos\\' => 'Classes',
		),
	),
);

?>
