<a href="http://aimeos.org/">
    <img src="http://aimeos.org/fileadmin/template/icons/logo.png" alt="Aimeos logo" title="Aimeos" align="right" height="60" />
</a>

Aimeos TYPO3 extension
======================

The repository contains the TYPO3 extension integrating the Aimeos e-commerce
library into TYPO3. The extension provides plugins for e.g. filter, list and
detail views, for searching products as well as baskets and the checkout process.

[![Aimeos Symfony2 demo](http://aimeos.org/fileadmin/user_upload/typo3-demo.jpg)](http://typo3.demo.aimeos.org/)

## Table of content

- [Installation](#installation)
    - [Composer](#composer)
    - [TYPO3](#typo3)
    - [Database](#database)
- [Setup](#setup)
    - [Upload the page tree file](#upload-the-page-tree-file)
    - [Go to the import view](#go-to-the-import-view)
    - [Import the uploaded page tree file](#import-the-uploaded-page-tree-file)
- [License](#license)
- [Links](#links)

## Installation

### Composer

The Aimeos TYPO3 extension repository only contains the code of the TYPO3 extension itself but no extensions or libraries it depends on. They can be installed by executing the [phing](http://phing.info/) command which uses [composer](http://getcomposer.org/) to install the missing pieces:

```
php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
phing update
```

If you don't have phing available, you can also execute the required steps by hand:
```
php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
git pull https://github.com/aimeos/aimeos-typo3.git
COMPOSER=composer.aimeos.json composer update
cp -r Resources/Libraries/aimeos/aimeos-core/client/html/themes/ Resources/Public/Themes/
```
### TYPO3

* Log into the TYPO3 back end
* Click on ''Admin Tools::Extension Manager'' in the left navigation
* Click the icon with the little plus sign left from the Aimeos list entry (looks like a lego brick)
* If a pop-up opens (only TYPO3 4.x) choose ''Make updates'' and "Close window" after the installation is done

**Caution:** Install the **RealURL extension before the Aimeos extension** to get nice looking URLs. Otherwise, RealURL doesn't rewrite the parameters even if you install RealURL afterwards!

![Install Aimeos TYPO3 extension](http://docs.aimeos.org/images/Aimeos-typo3-extmngr-install.png)

### Database

Afterwards, you have to execute the update script of the extension to create the required database structure:

![Execute update script](http://docs.aimeos.org/images/Aimeos-typo3-extmngr-update-6.x.png)

## Setup

The page setup for an Aimeos web shop is easy if you import the [standard page tree](http://aimeos.org/fileadmin/download/Aimeos-pages_three-columns_1.3.t3d) into your TYPO3 installation.

### Upload the page tree file

* In "File::Filelist"
* Select the "fileadmin" folder in the middle column
* Click on the upload icon on top of the right column
* Choose the file you've downloaded
* Press the "Upload files" button

![Upload the page tree file](http://docs.aimeos.org/images/Aimeos-typo3-pages-upload.png)

### Go to the import view

* In Web::Page, root page (the one with the globe)
* Right click on the globe
* Move the cursor to "Branch actions"
* In the sub-menu, click on "Import from .t3d"

![Go to the import view](http://docs.aimeos.org/images/Aimeos-typo3-pages-menu.png)

### Import the uploaded page tree file

* In Import / Export view
* Select the uploaded file from the drop-down menu
* Click on the "Preview" button
* The pages that will be imported are shown below
* Click on the "Import" button that has appeared
* Confirm to import the pages

![Import the uploaded page tree file](http://docs.aimeos.org/images/Aimeos-typo3-pages-import.png)

Now you have a new page "Shop" in your page tree including all required sub-pages.

## License

The Aimeos TYPO3 extension is licensed under the terms of the GPL Open Source
license and is available for free.

## Links

* [Web site](http://aimeos.org/app/typo3-ecommerce-shop/)
* [Documentation](http://docs.aimeos.org/TYPO3)
* [Help](http://help.aimeos.org/)
* [Issue tracker](https://github.com/aimeos/aimeos-typo3/issues)
* [Source code](https://github.com/aimeos/aimeos-typo3)
