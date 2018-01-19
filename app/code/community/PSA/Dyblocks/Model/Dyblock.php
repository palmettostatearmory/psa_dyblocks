<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Model_Dyblock extends Mage_Core_Model_Abstract {
    
    protected function _construct() {
        $this->_init('psa_dyblocks/dyblock');
    }
    
    public function isApplicable($product) {
        
        $prodData = $product->getData();
        
        // SKU logic
        $includeSkus = $this->getIncludeSkus();
        if(isset($includeSkus)) {
            $includeSkusArray = explode(',', $this->getIncludeSkus());
            if(!in_array($prodData['sku'], $includeSkusArray)) {
                return false;
            }
        }
      
        // Attribute logic
        $attrCode = $this->getAttrCode();
        $attrValue = $this->getAttrValue();
        
        if ($attrCode) {
            
            if (!array_key_exists($attrCode, $prodData)) { // has attribute condition
                return false;
            }
            
            $prodAttrValue = $prodData[$attrCode];
            
            if (preg_match('/^[0-9,]+$/', $prodAttrValue)){    
                if (!in_array($attrValue, explode(',', $prodAttrValue))){
                    return false;                 
                }  
            }
            elseif ($prodAttrValue != $attrValue){
                return false; 
            }  
            
        }
        
        
        // Date logic
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();
        
        $now = Mage::getModel('core/date')->date();
        if (($fromDate && $now < $fromDate) || ($toDate && $now > $toDate)) {
        	return false;
        }
        
        
        
        
        // Category logic
        $catIds = $this->getCategoryIds();
        if ($catIds) {
            $ids = $product->getCategoryIds();
            if (!is_array($ids)){
                return false;
            }
            $found = false;
            foreach (explode(',', $catIds) as $catId) {
                if (in_array($catId, $ids)) {
                    $found = true;
                }
            }
            if (!$found) {
                return false;
            }
        }
        
        
        // finally ...    
        return true;
          
    }
    
    
}