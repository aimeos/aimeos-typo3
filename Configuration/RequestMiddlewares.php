<?php

return [
	'frontend' => [
		'aimeos/shop-page-routing' => [
			'target' => \Aimeos\Aimeos\Middleware\PageRoutingHandler::class,
			'before' => [
				'typo3/cms-frontend/prepare-tsfe-rendering',
			],
			'after' => [
				'typo3/cms-frontend/tsfe',
			],
		],
	]
];