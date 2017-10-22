<?php

return array(
	'common' => array(
		'media' => array(
			'standard' => array(

				// Mime icon related settings
				'mimeicon' => array(
					// Directory where icons for the mime types stored Must be relative to the path in "basedir"
					'directory' => '../../typo3conf/ext/aimeos/Resources/Public/Images/Mimeicons',

					// File extension of mime type icons
					// 'extension' => '.png',
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
	),
);
