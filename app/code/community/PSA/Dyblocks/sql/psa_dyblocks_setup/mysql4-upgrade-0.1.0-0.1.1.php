<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$installer = $this;
$installer->startSetup();


$installer->run("
    ALTER TABLE `{$installer->getTable('psa_dyblocks/dyblock')}` ADD COLUMN `include_skus` TEXT NULL AFTER `block_position`;
");

    
$installer->endSetup();
