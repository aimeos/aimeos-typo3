.. include:: Includes.txt


.. _configuration:

Configuration Reference
=======================

Besides the huge list of possible `configuration options <http://aimeos.org/docs/latest/>`_
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

page.includeCSS.aimeos-common
"""""""""""""""""""""""""""""

:typoscript:`page.includeCSS.aimeos-common` = EXT:aimeos/Resources/Public/Themes/elegance/common.css

Location of the shared theme CSS file for the layout and the e-mails.


page.includeCSS.aimeos
""""""""""""""""""""""

:typoscript:`page.includeCSS.aimeos` = EXT:aimeos/Resources/Public/Themes/elegance/aimeos.css

Location of the theme CSS file for the layout.


page.includeJSFooterlibs.jquery
"""""""""""""""""""""""""""""""

:typoscript:`page.includeJSFooterlibs.jquery` = http://code.jquery.com/jquery-3.5.1.min.js

Location of the jQuery Javascript library.


page.includeJSFooter.tx_aimeos
""""""""""""""""""""""""""""""

:typoscript:`page.includeJSFooter.aimeos` = EXT:aimeos/Resources/Public/Themes/aimeos.js

Location of the shared Aimeos Javascript file which contains the common code for all themes.


page.includeJSFooter.aimeos-detail
""""""""""""""""""""""""""""""""""

:typoscript:`page.includeJSFooter.aimeos-detail` = EXT:aimeos/Resources/Public/Themes/aimeos-detail.js

Location of the Aimeos Javascript file for the detail page shared by all themes.


page.includeJSFooter.aimeos-theme
"""""""""""""""""""""""""""""""""

:typoscript:`page.includeJSFooter.aimeos-theme` = EXT:aimeos/Resources/Public/Themes/elegance/aimeos.js

Location of the theme specific Aimeos Javascript file.
