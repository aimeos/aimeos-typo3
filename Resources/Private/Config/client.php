<?php

return array(
	'html' => array(
		// Controller actions used by the documented TYPO3 routes, otherwise they are derived
		// from the config keys ("lists", "standard", "session") and the routes don't match
		'basket' => array(
			'standard' => array(
				'url' => array(
					'action' => 'index',
				),
			),
		),
		'catalog' => array(
			'lists' => array(
				'url' => array(
					'action' => 'list',
				),
			),
			'session' => array(
				'pinned' => array(
					'url' => array(
						'action' => 'detail',
					),
				),
			),
		),
		'checkout' => array(
			'standard' => array(
				'url' => array(
					'action' => 'index',
				),
			),
		),
	),
);
