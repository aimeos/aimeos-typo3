# Extend fe_users by fields required by Aimeos TYPO3 extension like sr_feuser_register does

CREATE TABLE fe_users (
    siteid int(11) DEFAULT NULL,
    static_info_country char(3) DEFAULT '' NOT NULL,
    zone varchar(45) DEFAULT '' NOT NULL,
    language char(2) DEFAULT '' NOT NULL,
    gender int(11) unsigned DEFAULT '99' NOT NULL,
    name varchar(100) DEFAULT '' NOT NULL,
    first_name varchar(64) DEFAULT '' NOT NULL,
    last_name varchar(64) DEFAULT '' NOT NULL,
    zip varchar(20) DEFAULT '' NOT NULL,
    date_of_birth int(11) DEFAULT '0' NOT NULL,
    vatid varchar(32) DEFAULT '' NOT NULL,
    longitude decimal(8,6) DEFAULT NULL,
    latitude decimal(8,6) DEFAULT NULL,
    vdate date DEFAULT NULL,
);
