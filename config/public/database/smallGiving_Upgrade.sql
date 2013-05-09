DROP TABLE IF EXISTS `charityGifts`;
CREATE TABLE  `charityGifts` (
  `charityID` int(10) unsigned NOT NULL auto_increment,
  `giftID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`charityID`,`giftID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `charityUpdates`;
CREATE TABLE  `charityUpdates` (
  `updateID` int(11) NOT NULL,
  `charityID` int(11) default NULL,
  `postID` int(11) NOT NULL,
  `postTags` varchar(255) default NULL,
  PRIMARY KEY  (`updateID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `donor`;
CREATE TABLE  `donor` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `sendUpdates` tinyint(1) NOT NULL default '0',
  `firstName` varchar(255) NOT NULL default '',
  `lastName` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `EE_EMAIL_TEMPLATE`;
CREATE TABLE  `EE_EMAIL_TEMPLATE` (
  `ID` bigint(20) NOT NULL,
  `MAIL_SUBJECT` varchar(255) default NULL,
  `MAIL_CONTENT` varchar(255) default NULL,
  `mail_type_id` int(10) default NULL,
  `blog_id` bigint(20) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `mail_type_key` (`mail_type_id`),
  KEY `blog_id_key` (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gift`;
CREATE TABLE  `gift` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `displayName` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `unitAmount` double NOT NULL default '0',
  `tags` varchar(255) NOT NULL default '',
  `blog_id` bigint(20) NOT NULL,
  `unitsWanted` int(10) unsigned NOT NULL default '0',
  `unitsDonated` int(10) unsigned NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  `previos_gift` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `gift_blog_id` (`blog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='InnoDB free: 0 kB';

DROP TABLE IF EXISTS `gift_donation_tags`;
CREATE TABLE  `gift_donation_tags` (
  `donationID` int(10) unsigned NOT NULL default '0',
  `termID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`donationID`,`termID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gift_Donations`;
CREATE TABLE  `gift_Donations` (
  `donationID` int(10) unsigned NOT NULL auto_increment,
  `giftID` int(10) unsigned NOT NULL default '0',
  `donorID` varchar(45) NOT NULL default '',
  `statusID` int(10) unsigned NOT NULL default '0',
  `transactionDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`donationID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_template_type`;
CREATE TABLE  `mail_template_type` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mail_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


