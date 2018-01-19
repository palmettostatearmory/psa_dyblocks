<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Block_Adminhtml_Dyblocks_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
    
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'psa_dyblocks';
        $this->_controller = 'adminhtml_dyblocks';

        $this->_updateButton('save', 'label', Mage::helper('psa_dyblocks')->__('Save Block'));
        $this->_updateButton('delete', 'label', Mage::helper('psa_dyblocks')->__('Delete Block'));
        $this->_addButton('save_edit', array(
                                       'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
                                       'onclick'   => 'editForm.submit(\''.$this->getSaveAndContinueUrl().'\');',
                                       'class'     => 'save',
                                  ), 1);

        if( $this->getRequest()->getParam($this->_objectId) ) {
            $model = Mage::getModel('psa_dyblocks/dyblock')
                ->load($this->getRequest()->getParam($this->_objectId));
        }
        
        $this->_formScripts[] = " function showOptions(sel) {
            Element.show('loading-mask');
            if(document.getElementById('attr_value')) {
                document.getElementById('attr_value').id = 'dyblock_attr_value';
            }
            Element.hide('loading-mask');
            new Ajax.Request('" . $this->getUrl('*/*/options', array('isAjax'=>true)) ."', {
                parameters: {code : sel.value},
                onSuccess: function(transport) {
                    $('dyblock_attr_value').up().update(transport.responseText);
                }
            });
        }";
        
        
        $this->_formScripts[] = "function switchcheck(source) {
            checkboxes = document.getElementsByName('category_ids[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
          }";
        

    }

    public function getHeaderText()
    {
        $header = 'Edit Dynamic Product Block';
        
        return $header;
    }

    private function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
                                              'back'       => 'edit',
                                              $this->_objectId => $this->getRequest()->getParam($this->_objectId)
                                         ));
    }
    
}