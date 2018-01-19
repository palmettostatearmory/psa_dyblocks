<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Helper_Data extends Mage_Core_Helper_Abstract{
    
    public function getBlocks($position, $product) {
        
        $blocks = array();
        
        $collection = Mage::getResourceModel('psa_dyblocks/dyblock_collection');
        
        $collection->addFieldToFilter('block_position', array('eq' => $position))
                   ->addFieldToFilter('is_active', array('eq' => '1'))
                   ->addOrder('sort_order', 'ASC');
        
        foreach($collection as $rule) {
            if($rule->isApplicable($product)) {
                 $content = Mage::helper('cms')->getPageTemplateProcessor()->filter($rule->getContent());
                 $blocks[] = $content;
            }
        }
        
        return $blocks;
        
    }
    
    
    
    
    /*
     * Returns true if module output is disabled from the admin section
     * @return bool
     */
    public static function isModuleOutputDisabled()
    {
        return (bool) Mage::getStoreConfig('advanced/modules_disable_output/PSA_Dyblocks');
    }
   
    
}
