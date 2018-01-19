<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$installer = $this;
$installer->startSetup();


$installer->run("
    CREATE TABLE `{$installer->getTable('psa_dyblocks/dyblock')}` (
        `dyblock_id` INT(10) NOT NULL AUTO_INCREMENT,
	`block_title` VARCHAR(255) NOT NULL,
	`block_position` VARCHAR(255) NOT NULL,
	`category_ids` TEXT NULL,
	`is_active` TINYINT(1) NOT NULL DEFAULT '1',
	`from_date` DATE NULL DEFAULT '0000-00-00',
	`to_date` DATE NULL DEFAULT '0000-00-00',
	`sort_order` INT(10) NOT NULL DEFAULT '0',
	`attr_code` VARCHAR(255) NULL DEFAULT NULL,
	`attr_value` VARCHAR(255) NULL DEFAULT NULL,
	`content` TEXT NULL,
	PRIMARY KEY (`dyblock_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

    
$installer->endSetup();