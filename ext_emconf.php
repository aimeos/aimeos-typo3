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
	'description' => 'Aimeos is a flexible, high performance shop system available in multiple languages. Several plugins e.g. for facetted search, product listing, detail view, basket, checkout process and other parts of a shop are available. Furthermore, Aimeos offers powerful interfaces to integrate CRM and ERP systems as well as payment and delivery service providers',
	'category' => 'plugin',
	'version' => '2.0.0',
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
			'php' => '5.3.2-5.99.99',
			'typo3' => '6.1.0-7.0.99',
			'fluid' => '6.1.0-7.0.99',
			'extbase' => '6.1.0-7.0.99',
			'scheduler' => '6.1.0-7.0.99',
			'static_info_tables' => '6.0.0-7.99.99',
		),
		'conflicts' => array(
			'jquerycolorbox' => '0.0.0-0.0.1',
		),
		'suggests' => array(
			'realurl' => '1.12.8-1.99.99',
		),
	),
);

?>
