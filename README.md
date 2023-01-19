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

![aimeos-frontend](https://user-images.githubusercontent.com/8647429/212348410-55cbaa00-722a-4a30-8b57-da9e173e0675.jpg)

## Table Of Content

- [Installation](#installation)
    - [Composer](#composer)
    - [DDev or Colima](#ddev)
    - [TER](#ter-extension)
- [TYPO3 setup](#typo3-setup)
    - [Database setup](#database-setup)
    - [Security](#security)
- [Page setup](#page-setup)
    - [Download the Aimeos Page Tree t3d file](#download-the-aimeos-page-tree-t3d-file)
    - [Go to the Import View](#go-to-the-import-view)
    - [Upload the page tree file](#upload-the-page-tree-file)
    - [Go to the import view](#go-to-the-import-view)
    - [Import the page tree](#import-the-page-tree)
    - [SEO-friendly URLs](#seo-friendly-urls)
- [License](#license)
- [Links](#links)

## Installation

This document is for the latest Aimeos TYPO3 **21.10 release and later**.

- LTS release: 22.10 (TYPO3 11 LTS)
- old LTS release: 21.10 (TYPO3 10/11 LTS)

### Composer

**Note:** composer 2.1+ is required!

The latest TYPO3 version can be installed via composer. This is especially useful, if you want to create new TYPO3 installations automatically or play with the latest code. You need to install the composer package first, if it isn't already available:

```bash
php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
```

To install the TYPO3 base distribution first, execute this command:

```bash
composer create-project typo3/cms-base-distribution myshop
# or install a specific TYPO3 version:
composer create-project "typo3/cms-base-distribution:^11" myshop
```

It will install TYPO3 into the `./myshop/` directory. Change into the directory and install TYPO3 as usual:

```bash
cd ./myshop
touch public/FIRST_INSTALL
```

Open the TYPO3 URL in your browser and follow the setup steps. Afterwards, install the Aimeos extension using:

```bash
composer req aimeos/aimeos-typo3:~22.10
```

If composer complains that one or more packages can't be installed because the required minimum stability isn't met, add this to your `composer.json`:

```json
"minimum-stability": "dev",
"prefer-stable": true,
```

If you want a more or less working installation out of the box for new installations, you can install the Bootstrap package too:

```bash
composer req bk2k/bootstrap-package
```

***Note***: Remember to create a root page and a root template, which includes the Bootstrap package templates! (See also below.)

Finally, depending on your TYPO3 version, run the following commands from your installation directory:

**For TYPO3 11:**

```bash
php ./vendor/bin/typo3 extension:setup
php ./vendor/bin/typo3 aimeos:setup --option=setup/default/demo:1
```

If you don't want to add the Aimeos demo data, you should remove `--option=setup/default/demo:1` from the Aimeos setup command.

**For TYPO3 10:**

```bash
php ./vendor/bin/typo3 extension:activate scheduler
php ./vendor/bin/typo3 extension:activate aimeos
```

If you experience any errors with the database, please check the [Database Setup](#database-setup) section below.

Please keep on reading below the "TER Extension" installation section!

### DDev

*Note:* Installation instructions for TYPO3 with `ddev` or `Colima` can be found here:
[TYPO3 with ddev or colima](https://ddev.readthedocs.io/en/latest/users/quickstart/)

### TER Extension

If you want to install Aimeos into a traditionally installed TYPO3 ("legacy installation"), the [Aimeos extension from the TER](https://typo3.org/extensions/repository/view/aimeos) is recommended. You can download and install it directly from the Extension Manager of your TYPO3 instance.

* Log into the TYPO3 backend
* Click on "Admin Tools::Extensions" in the left navigation
* Click the icon with the little plus sign left from the Aimeos list entry

![Install Aimeos TYPO3 extension](https://user-images.githubusercontent.com/213803/211545083-d0820b63-26f2-453e-877f-ecd5ec128713.jpg)

Afterwards, you have to execute the update script of the extension to create the required database structure:

* Click on "Admin Tools::Upgrade"
* Click "Run Upgrade Wizard" in the "Upgrade Wizard" tile
* Click "Execute"

![Execute update script](https://user-images.githubusercontent.com/213803/211545122-8fd94abd-78b2-47ad-ad3c-1ef1b9c052b4.jpg)

#### Aimeos Distribution

For new TYPO3 installations, there is a 1-click [Aimeos distribution](https://typo3.org/extensions/repository/view/aimeos_dist) available, too. Choose the Aimeos distribution from the list of available distributions in the Extension Manager and you will get a completely set up shop system including demo data for a quick start.

## TYPO3 Setup

Setup TYPO3 by creating a `FIRST_INSTALL` file in the `./public` directory:

```bash
touch public/FIRST_INSTALL
```

Open the URL of your installation in the browser and follow the steps in the TYPO3 setup scripts.

### Database Setup

If you use MySQL < 5.7.8, you have to use `utf8` and `utf8_unicode_ci` instead because those MySQL versions can't handle the long indexes created by `utf8mb4` (up to four bytes per character) and you will get errors like

```
1071 Specified key was too long; max key length is 767 bytes
```

To avoid that, change your database settings in your `./typo3conf/LocalConfiguration.php` to:

```php
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

```php
    'FE' => [
        'cookieSameSite' => 'none'
    ]
```

## Site Setup

TYPO3 10+ requires a site configuration which you have to add in "Site Management" > "Sites" available in the left navigation. When creating a root page (a page with a globe icon), a basic site configuration is automatically created (see below at [Go to the Import View](#go-to-the-import-view)).

## Page Setup

### Download the Aimeos Page Tree t3d file

The page setup for an Aimeos web shop is easy, if you import the example page tree for TYPO3 10/11. You can download the version you need from here:

* [22.10+ page tree](https://aimeos.org/fileadmin/download/Aimeos-pages_2022.10.t3d) and later
* [21.10 page tree](https://aimeos.org/fileadmin/download/Aimeos-pages_21.10.t3d)

**Note:** The Aimeos layout expects [Bootstrap](https://getbootstrap.com) providing the grid layout!

In order to upload and install the file, follow the following steps:

### Go to the Import View

**Note:** It is recommended to import the Aimeos page tree to a page that is defined as "root page". To create a root page, simply create a new page and, in the "Edit page properties", activate the "Use as Root Page" option under "Behaviour". The icon of the root page will change to a globe. This will also create a basic site configuration. Don't forget to also create a typoscript root template and include the bootstrap templates with it!

![Create a root page](https://user-images.githubusercontent.com/213803/211549273-1d3883dd-710c-4e27-8dbb-3de6e45680d7.jpg)

* In "Web::Page", right-click on the root page (the one with the globe)
* Click on "More options..."
* Click on "Import"

![Go to the import view](https://user-images.githubusercontent.com/213803/211550212-df6daa73-74cd-459e-8d25-a56c413c175d.jpg)

### Upload the page tree file

* In the page import dialog
* Select the "Upload" tab (2nd one)
* Click on the "Select" dialog
* Choose the T3D file you've downloaded
* Press the "Upload files" button

![Upload the page tree file](https://user-images.githubusercontent.com/8647429/212347778-17238e05-7494-4413-adb3-a54b2b524e05.png)

### Import the page tree

* In Import / Export view
* Select the uploaded file from the drop-down menu
* Click on the "Preview" button
* The pages that will be imported are shown below
* Click on the "Import" button that has appeared
* Confirm to import the pages

![Import the uploaded page tree file](https://user-images.githubusercontent.com/8647429/212348040-c3e10b60-5579-4d1b-becc-72548826c6db.png)

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
