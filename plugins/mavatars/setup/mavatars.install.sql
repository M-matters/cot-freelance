CREATE TABLE IF NOT EXISTS `cot_mavatars` (
    `mav_id` int(11) NOT NULL AUTO_INCREMENT,
    `mav_userid` int(11) NOT NULL,
	`mav_extension` varchar(255) collate utf8_unicode_ci,
	`mav_category` varchar(255) collate utf8_unicode_ci,
    `mav_code` varchar(255) collate utf8_unicode_ci,
    `mav_item` int(11) NOT NULL,
	`mav_filepath` varchar(255) collate utf8_unicode_ci,
	`mav_filename` varchar(255) collate utf8_unicode_ci,
	`mav_fileext` varchar(255) collate utf8_unicode_ci,
	`mav_fileorigname` varchar(255) collate utf8_unicode_ci,
	`mav_thumbpath` varchar(255) collate utf8_unicode_ci,
    `mav_filesize` int(11) NOT NULL,
	`mav_desc` varchar(255) NOT NULL,
	`mav_order` int(11) NOT NULL,
	`mav_type` varchar(255) NOT NULL,
    PRIMARY KEY (`mav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
