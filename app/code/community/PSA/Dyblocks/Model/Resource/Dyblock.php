<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Model_Resource_Dyblock extends Mage_Core_Model_Resource_Db_Abstract {
    
    protected function _construct() {
        $this->_init('psa_dyblocks/dyblock', 'dyblock_id');
    }
    
    
}