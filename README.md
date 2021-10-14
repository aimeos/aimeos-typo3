<a href="https://aimeos.org/">
    <img src="https://aimeos.org/fileadmin/template/icons/logo.png" alt="Aimeos logo" title="Aimeos" align="right" height="60" />
</a>

# Aimeos TYPO3 extension

[![Total Downloads](https://poser.pugx.org/aimeos/aimeos-typo3/d/total.svg)](https://packagist.org/packages/aimeos/aimeos-typo3)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aimeos/aimeos-typo3/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aimeos/aimeos-typo3/?branch=master)
[![License](https://poser.pugx.org/aimeos/aimeos-typo3/license.svg)](https://packagist.org/packages/aimeos/aimeos-typo3)

:star: Star us on GitHub — it motivates us a lot!

[Aimeos](https://aimeos.org/TYPO3) is THE professional, full-featured and
high performance e-commerce extension for TYPO3!  You can install it in your
existing TYPO3 web site within 5 minutes and can adapt, extend, overwrite
and customize anything to your needs.

[![Aimeos TYPO3 demo](https://aimeos.org/fileadmin/user_upload/typo3-demo.jpg)](http://typo3.demo.aimeos.org/)

## Table of content

- [Installation](#installation)
    - [TER](#typo3-extension-repository)
    - [Composer](#composer)
- [TYPO3 setup](#typo3-setup)
    - [Extension](#extension)
    - [Database](#database)
- [Page setup](#page-setup)
    - [Upload the page tree file](#upload-the-page-tree-file)
    - [Go to the import view](#go-to-the-import-view)
    - [Import the page tree](#import-the-page-tree)
    - [SEO-friendly URLs](#seo-friendly-urls)
- [License](#license)
- [Links](#links)

## Installation

This document is for the latest Aimeos TYPO3 **21.10 release and later**.

- LTS release: 21.10 (TYPO3 10/11 LTS)

### Composer

**Note:** composer 2.1+ is required!

The latest version can be installed via composer too. This is especially useful if you want to create new TYPO3 installations automatically or play with the latest code. You need to install the composer package first if it isn't already available:

`php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer`

In order to tell install TYPO3, you have to execute

`composer create-project typo3/cms-base-distribution myshop`

This will install TYPO3 into the `./myshop/` directory.

Then, change to the `./mshop/` directory and install the Aimeos extension for TYPO3 with:

`composer req aimeos/aimeos-typo3:~21.10`

This will install stable TYPO3 version and the latest Aimeos TYPO3 extension. If you want a more or less working installation out of the box for new installations, you should install the Bootstrap package too:

`composer req bk2k/bootstrap-package`

### TER extension

If you want to install Aimeos into your existing TYPO3 installation, the [Aimeos extension from the TER](https://typo3.org/extensions/repository/view/aimeos) is recommended. You can download and install it directly from the Extension Manager of your TYPO3 instance.

For new TYPO3 installations, there's a 1-click [Aimeos distribution](https://typo3.org/extensions/repository/view/aimeos_dist) available too. Choose the Aimeos distribution from the list of available distributions in the Extension Manager and you will get a completely set up shop system including demo data for a quick start.

## TYPO3 setup

Setup TYPO3 as normal by creating a `FIRST_INSTALL` file in the `./public` directory:

```bash
touch public/FIRST_INSTALL
```

Open the URL of your installation in the browser and follow the steps in the TYPO3 setup scripts.

### Database setup

If you use MySQL < 5.7.8, you have to use `utf8` and `utf8_unicode_ci` instead because those MySQL versions can't handle the long indexes created by `utf8mb4` (up to four bytes per character) and you will get errors like

```
1071 Specified key was too long; max key length is 767 bytes
```

To avoid that, change your database settings in your `./typo3conf/LocalConfiguration.php` to:

```
'DB' => [
    'Connections' => [
        'Default' => [
            'tableoptions' => [
                'charset' => 'utf8',
                'collate' => 'utf8_unicode_ci',
            ],
            // ...
        ],
    ],
],
```

### Security

Since **TYPO3 9.5.14+** implements **SameSite cookie handling** and restricts when browsers send cookies to your site. This is a problem when customers are redirected from external payment provider domain. Then, there's no session available on the confirmation page. To circumvent that problem, you need to set the configuration option `cookieSameSite` to `none` in your `./typo3conf/LocalConfiguration.php`:

```
    'FE' => [
        'cookieSameSite' => 'none'
    ]
```

### Composer

#### TYPO3 11

When using TYPO3 11, you have to run these commands from your installation directory:

```bash
php ./vendor/bin/typo3 extension:setup
php ./vendor/bin/typo3 aimeos:setup --option=setup/default/demo:1
```

If you don't want to add the Aimeos demo data, you should remove `--option=setup/default/demo:1` from the Aimeos setup command.

#### TYPO3 10

For TYPO3 10, these commands are required:

```bash
php ./vendor/bin/typo3 extension:activate scheduler
php ./vendor/bin/typo3 extension:activate aimeos
```

### TER Extension

* Log into the TYPO3 back end
* Click on ''Admin Tools::Extension Manager'' in the left navigation
* Click the icon with the little plus sign left from the Aimeos list entry (looks like a lego brick)

![Install Aimeos TYPO3 extension](https://aimeos.org/docs/images/Aimeos-typo3-extmngr-install.png)

Afterwards, you have to execute the update script of the extension to create the required database structure:

![Execute update script](https://aimeos.org/docs/images/Aimeos-typo3-extmngr-update-7.x.png)

## Site setup

TYPO3 10+ requires a site configuration which you have to add in "Site Management" > "Sites" available in the left navigation.

## Page setup

The page setup for an Aimeos web shop is easy if you import the example page tree for TYPO3 10/11:

* [21.10+ page tree](https://aimeos.org/fileadmin/download/Aimeos-pages_21.10.t3d) only

**Note:** The Aimeos layout expects [Bootstrap](https://getbootstrap.com) providing the grid layout!

### Go to the import view

* In Web::Page, root page (the one with the globe)
* Right click on the globe
* Move the cursor to "Branch actions"
* In the sub-menu, click on "Import from .t3d"

![Go to the import view](https://aimeos.org/docs/images/Aimeos-typo3-pages-menu.png)

### Upload the page tree file

* In the page import dialog
* Select the "Upload" tab (2nd one)
* Click on the "Select" dialog
* Choose the file you've downloaded
* Press the "Upload files" button

![Upload the page tree file](https://aimeos.org/docs/images/Aimeos-typo3-pages-upload.png)

### Import the page tree

* In Import / Export view
* Select the uploaded file from the drop-down menu
* Click on the "Preview" button
* The pages that will be imported are shown below
* Click on the "Import" button that has appeared
* Confirm to import the pages

![Import the uploaded page tree file](https://aimeos.org/docs/images/Aimeos-typo3-pages-import.png)

Now you have a new page "Shop" in your page tree including all required sub-pages.

### SEO-friendly URLs

TYPO3 9.5 and later can create SEO friendly URLs if you add the rules to the site config:
[https://aimeos.org/docs/latest/typo3/setup/#seo-urls](https://aimeos.org/docs/latest/typo3/setup/#seo-urls)

## License

The Aimeos TYPO3 extension is licensed under the terms of the GPL Open Source
license and is available for free.

## Links

* [Web site](https://aimeos.org/integrations/typo3-shop-extension/)
* [Documentation](https://aimeos.org/docs/TYPO3)
* [Forum](https://aimeos.org/help/typo3-extension-f16/)
* [Issue tracker](https://github.com/aimeos/aimeos-typo3/issues)
* [Source code](https://github.com/aimeos/aimeos-typo3)
