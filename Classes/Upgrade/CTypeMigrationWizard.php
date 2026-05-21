<?php

namespace Aimeos\Aimeos\Upgrade;

use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;


class CTypeMigrationWizard extends AbstractListTypeToCTypeUpdate
{
    public function getIdentifier(): string
    {
        return 'aimeos_ctypeMigration';
    }

    public function getTitle(): string
    {
        return 'Aimeos: Migrate list plugins to individual CTypes';
    }

    public function getDescription(): string
    {
        return 'Migrates Aimeos content elements from CType=list to individual CTypes';
    }

    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'aimeos_jsonapi' => 'aimeos_jsonapi',
            'aimeos_locale-select' => 'aimeos_localeselect',
            'aimeos_catalog-attribute' => 'aimeos_catalogattribute',
            'aimeos_catalog-count' => 'aimeos_catalogcount',
            'aimeos_catalog-detail' => 'aimeos_catalogdetail',
            'aimeos_catalog-filter' => 'aimeos_catalogfilter',
            'aimeos_catalog-home' => 'aimeos_cataloghome',
            'aimeos_catalog-list' => 'aimeos_cataloglist',
            'aimeos_catalog-price' => 'aimeos_catalogprice',
            'aimeos_catalog-search' => 'aimeos_catalogsearch',
            'aimeos_catalog-session' => 'aimeos_catalogsession',
            'aimeos_catalog-stage' => 'aimeos_catalogstage',
            'aimeos_catalog-stock' => 'aimeos_catalogstock',
            'aimeos_catalog-suggest' => 'aimeos_catalogsuggest',
            'aimeos_catalog-supplier' => 'aimeos_catalogsupplier',
            'aimeos_catalog-tree' => 'aimeos_catalogtree',
            'aimeos_basket-bulk' => 'aimeos_basketbulk',
            'aimeos_basket-related' => 'aimeos_basketrelated',
            'aimeos_basket-small' => 'aimeos_basketsmall',
            'aimeos_basket-standard' => 'aimeos_basketstandard',
            'aimeos_checkout-standard' => 'aimeos_checkoutstandard',
            'aimeos_checkout-confirm' => 'aimeos_checkoutconfirm',
            'aimeos_checkout-update' => 'aimeos_checkoutupdate',
            'aimeos_account-basket' => 'aimeos_accountbasket',
            'aimeos_account-download' => 'aimeos_accountdownload',
            'aimeos_account-history' => 'aimeos_accounthistory',
            'aimeos_account-favorite' => 'aimeos_accountfavorite',
            'aimeos_account-review' => 'aimeos_accountreview',
            'aimeos_account-profile' => 'aimeos_accountprofile',
            'aimeos_account-subscription' => 'aimeos_accountsubscription',
            'aimeos_account-watch' => 'aimeos_accountwatch',
            'aimeos_supplier-detail' => 'aimeos_supplierdetail',
        ];
    }
}
