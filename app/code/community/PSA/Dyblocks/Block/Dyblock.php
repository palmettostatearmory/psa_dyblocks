<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Block_Dyblock extends Mage_Core_Block_Template {
    
    protected function _toHtml()
    {
        if(PSA_Dyblocks_Helper_Data::isModuleOutputDisabled()) {
            return '';
        }
        
        $product = Mage::registry('current_product');
        
        
        return implode('', Mage::helper('psa_dyblocks')->getBlocks(
                $this->getPosition(),
                $product
            ));
        
   
    }
    
}