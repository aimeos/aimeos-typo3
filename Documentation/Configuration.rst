.. include:: Includes.txt


.. _configuration:

Configuration Reference
=======================

Besides the huge list of possible `configuration options <https://docs.aimeos.org/Configuration>`_
in Aimeos, the TYPO3 extension has a some Typoscript configuration as well.

Target group: **Developers**


.. _configuration-typoscript:

TypoScript Reference
--------------------


.. only:: html

	.. contents::
		:local:
		:depth: 1


Properties
^^^^^^^^^^

page.includeCSS.tx_aimeos
"""""""""""""""""""""""""""

page.includeCSS.tx_aimeos = EXT:aimeos/Resources/Public/html/classic/css/arcavias.css

Location of the CSS file for the layout.


page.includeJSFooterlibs.jquery
"""""""""""""""""""""""""""""""

page.includeJSFooterlibs.jquery = EXT:aimeos/Resources/Public/html/classic/js/jquery.min.js

Location of the jQuery Javascript library.


page.includeJSFooterlibs.jquery-migrate
"""""""""""""""""""""""""""""""""""""""

page.includeJSFooterlibs.jquery-migrate = EXT:aimeos/Resources/Public/html/classic/js/jquery-migrate.js

Location of the compatibility layer for the jQuery Javascript library version 1.10 and above.


page.includeJSFooterlibs.jquery-ui
""""""""""""""""""""""""""""""""""

page.includeJSFooterlibs.jquery-ui = EXT:aimeos/Resources/Public/html/classic/js/jquery-ui.custom.min.js

Location of the customer jQuery UI library with additional effects and features.


page.includeJSFooter.tx_aimeos
""""""""""""""""""""""""""""""""

:typoscript:`page.includeJSFooter.tx_aimeos` = EXT:aimeos/Resources/Public/html/classic/js/arcavias.js

Location of the Arcavias Javascript file which contains the common code for the views.


client.html.common.content.baseurl
""""""""""""""""""""""""""""""""""

:typoscript:`plugin.tx_aimeos.client.html.common.content.baseurl` = uploads/tx_aimeos

Location of the uploaded media files


client.html.common.template.baseurl
"""""""""""""""""""""""""""""""""""

:typoscript:`plugin.tx_aimeos.client.html.common.template.baseurl` = typo3conf/ext/aimeos/Resources/Public/html/classic

Location of the CSS, Javascript and image files for the Arcavias front-end.
