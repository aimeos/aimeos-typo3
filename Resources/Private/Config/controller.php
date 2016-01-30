<?php

return array(
	'extjs' => array(
		'attribute' =>  array(
			'import' => array(
				'text' => array(
					'default' => array(
						'uploaddir' => PATH_site . 'typo3temp',
						'fileperms' => 0660,
					),
				),
			),
			'export' => array(
				'text' => array(
					'default' => array(
						'downloaddir' => '../typo3temp',
						'exportdir' => PATH_site . 'typo3temp',
					),
				),
			),
		),
		'catalog' =>  array(
			'import' => array(
				'text' => array(
					'default' => array(
						'uploaddir' => PATH_site . 'typo3temp',
						'fileperms' => 0660,
					),
				),
			),
			'export' => array(
				'text' => array(
					'default' => array(
						'downloaddir' => '../typo3temp',
						'exportdir' => PATH_site . 'typo3temp',
					),
				),
			),
		),
		'media' => array(
			'default' => array(

				// Base directory to the document root of the website, must be absolute by beginning with "/"
				'basedir' => PATH_site . 'uploads/tx_aimeos',

				// Upload related settings
				'upload' => array(
					// Media directory where the uploaded files will be stored, must be relative to the path in "basedir"
					'directory' => '',

					// Directory permissions (in octal notation) which are applied to newly created directories
					// 'dirperms' => 0775,

					// File permissions (in octal notation) which are applied to newly created files
					// 'fileperms' => 0664,
				),

				// Mime icon related settings
				'mimeicon' => array(
					// Directory where icons for the mime types stored Must be relative to the path in "basedir"
					'directory' => '../../typo3conf/ext/aimeos/Resources/Public/Images/Icons/mimetypes',

					// File extension of mime type icons
					// 'extension' => '.png',
				),

				// Unix commands executed on a shell
				'command' => array(
					// "file" command for identifying the mime type of a file
					'file' => 'PATH=/opt/local/bin/64:$PATH file -b --mime-type %1$s 2>&1',

					// ImageMagick "identfy" command for identifying the type of an image
					// 'identify' => 'identify -format "%%m" %1$s 2>&1',

					// ImageMagick "convert" command for converting an image
					// 'convert' => 'convert %1$s -resize %3$sx%4$s %2$s 2>&1',
				),

				// Parameter for images
				'files' => array(
					// Allowed image mime types, other image types will be converted
					// 'allowedtypes' => array( 'image/jpeg', 'image/png', 'image/gif' ),

					// Image type to which all other image types will be converted to
					// 'defaulttype' => 'jpeg',

					// Maximum width of an image
					// Image will be scaled up or down to this size without changing the
					// width/height ratio. A value of "null" doesn't scale the image or
					// doesn't restrict the size of the image if it's scaled due to a value
					// in the "maxheight" parameter
					// 'maxwidth' => null,

					// Maximum height of an image
					// Image will be scaled up or down to this size without changing the
					// width/height ratio. A value of "null" doesn't scale the image or
					// doesn't restrict the size of the image if it's scaled due to a value
					// in the "maxwidth" parameter
					// 'maxheight' => null,
				),

				// Parameter for preview images
				'preview' => array(
					// Allowed image mime types, other image types will be converted
					// 'allowedtypes' => array( 'image/jpeg', 'image/png', 'image/gif' ),

					// Image type to which all other image types will be converted to
					// 'defaulttype' => 'jpeg',

					// Maximum width of a preview image
					// Image will be scaled up or down to this size without changing the
					// width/height ratio. A value of "null" doesn't scale the image or
					// doesn't restrict the size of the image if it's scaled due to a value
					// in the "maxheight" parameter
					// 'maxwidth' => 360,

					// Maximum height of a preview image
					// Image will be scaled up or down to this size without changing the
					// width/height ratio. A value of "null" doesn't scale the image or
					// doesn't restrict the size of the image if it's scaled due to a value
					// in the "maxwidth" parameter
					// 'maxheight' => 280,
				),
			),
		),
		'product' =>  array(
			'import' => array(
				'text' => array(
					'default' => array(
						'uploaddir' => PATH_site . 'typo3temp',
						'fileperms' => 0660,
					),
				),
			),
			'export' => array(
				'text' => array(
					'default' => array(
						'downloaddir' => '../typo3temp',
						'exportdir' => PATH_site . 'typo3temp',
					),
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
);
