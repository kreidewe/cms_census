CREATE TABLE tx_cmscensus_domain_model_url
(
		name                   varchar(255) NOT NULL DEFAULT '',
		description            text         NOT NULL DEFAULT '',
		is_proposal            smallint(1) unsigned NOT NULL DEFAULT '0',
		only_next_auto_update  smallint(1) unsigned NOT NULL DEFAULT '0',
		every_auto_update      smallint(1) unsigned NOT NULL DEFAULT '0',
		is_auto_update_planned smallint(1) unsigned NOT NULL DEFAULT '0',
		categories             int(11) unsigned NOT NULL DEFAULT '0',
		whatcmstype            varchar(255) NOT NULL DEFAULT '',
		httpstatus             int(11) unsigned NOT NULL DEFAULT '200',
		checkflag              int(11) unsigned NOT NULL DEFAULT '0',
);

CREATE TABLE tx_cmscensus_domain_model_category
(
		name        varchar(255) NOT NULL DEFAULT '',
		description text         NOT NULL DEFAULT '',
		is_proposal smallint(1) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_cmscensus_domain_model_whatcmstype
(
		id    int(11) NOT NULL DEFAULT '0',
		label varchar(255) NOT NULL DEFAULT '',
		name  varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_cmscensus_domain_model_versions
(
		id    int(11) NOT NULL DEFAULT '0',
		token varchar(255) NOT NULL DEFAULT '',
		expirey  varchar(255) NOT NULL DEFAULT ''
);
