# Extend fe_users by fields required by Aimeos TYPO3 extension like sr_feuser_register does

CREATE TABLE fe_users (
    siteid int(11) DEFAULT NULL,
    static_info_country char(3) DEFAULT '' NOT NULL,
    zone varchar(45) DEFAULT '' NOT NULL,
    language char(2) DEFAULT '' NOT NULL,
    gender int(11) unsigned DEFAULT '99' NOT NULL,
    name varchar(100) DEFAULT '' NOT NULL,
    first_name varchar(50) DEFAULT '' NOT NULL,
    last_name varchar(50) DEFAULT '' NOT NULL,
    zip varchar(20) DEFAULT '' NOT NULL,
    date_of_birth int(11) DEFAULT '0' NOT NULL,
    vatid varchar(32) DEFAULT '' NOT NULL,
    longitude decimal(8,6) DEFAULT NULL,
    latitude decimal(8,6) DEFAULT NULL,
    vdate date DEFAULT NULL,
);


# Create cache tables for TYPO3 < 4.6

CREATE TABLE tx_aimeos_cache (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    content mediumblob,
    lifetime int(11) unsigned DEFAULT '0' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier)
) ENGINE=InnoDB;

CREATE TABLE tx_aimeos_cache_tags (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    tag varchar(250) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier),
    KEY cache_tag (tag)
) ENGINE=InnoDB;