<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Block_Adminhtml_Dyblocks extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'psa_dyblocks';
        $this->_controller = 'adminhtml_dyblocks';
        $this->_headerText = Mage::helper('psa_dyblocks')->__('Manage CMS Dynamic Blocks');
        $this->_addButtonLabel = Mage::helper('psa_dyblocks')->__('Add New Dynamic Block');
        parent::__construct();
    }
}