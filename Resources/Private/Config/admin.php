<?php

return array(
	'jqadm' => array(
		'url' => array(
			'batch' => array(
				'controller' => 'Jqadm',
				'action' => 'batch',
				'config' => array(
					'BE' => true,
				),
			),
			'copy' => array(
				'controller' => 'Jqadm',
				'action' => 'copy',
				'config' => array(
					'BE' => true,
				),
			),
			'create' => array(
				'controller' => 'Jqadm',
				'action' => 'create',
				'config' => array(
					'BE' => true,
				),
			),
			'delete' => array(
				'controller' => 'Jqadm',
				'action' => 'delete',
				'config' => array(
					'BE' => true,
				),
			),
			'export' => array(
				'controller' => 'Jqadm',
				'action' => 'export',
				'config' => array(
					'BE' => true,
				),
			),
			'get' => array(
				'controller' => 'Jqadm',
				'action' => 'get',
				'config' => array(
					'BE' => true,
				),
			),
			'import' => array(
				'controller' => 'Jqadm',
				'action' => 'import',
				'config' => array(
					'BE' => true,
				),
			),
			'save' => array(
				'controller' => 'Jqadm',
				'action' => 'save',
				'config' => array(
					'BE' => true,
				),
			),
			'search' => array(
				'controller' => 'Jqadm',
				'action' => 'search',
				'config' => array(
					'BE' => true,
				),
			),
		),
	),
	'jsonadm' => array(
		'url' => array(
			'controller' => 'Jsonadm',
			'action' => 'index',
			'config' => array(
				'absoluteUri' => true,
				'BE' => true,
			),
			'options' => array(
				'controller' => 'Jsonadm',
				'action' => 'index',
				'config' => array(
					'absoluteUri' => true,
					'BE' => true,
				),
			),
		),
	),
	'graphql' => array(
		'url' => array(
			'controller' => 'Graphql',
			'action' => 'index',
			'config' => array(
				'absoluteUri' => true,
				'BE' => true,
			),
		),
	),
);
