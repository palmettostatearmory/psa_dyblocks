<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Adminhtml_Psadyblocks_DyblocksController extends Mage_Adminhtml_Controller_Action {
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/psa_dyblocks');
    }
    
    public function indexAction() {
        
        $this->_title($this->__('PSA Dynamic CMS Blocks'));
        $this->loadLayout();
        $this->_setActiveMenu('cms/psa_dyblocks');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('PSA Dynamic CMS Blockss'), Mage::helper('adminhtml')->__('PSA Dynamic CMS Blocks'));
        $this->_addContent($this->getLayout()->createBlock('psa_dyblocks/adminhtml_dyblocks'));
        $this->renderLayout();
        
    }
    
    public function newAction()
    {
        $this->editAction();
    }
    
    public function editAction()
    {
        $this->loadLayout();

        $this->_setActiveMenu('cms/psa_dyblocks');
        $this->_title($this->__('Edit'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('CMS'), Mage::helper('adminhtml')->__('Dyblock'));

        $id = (int) $this->getRequest()->getParam('id');
        $model = Mage::getModel('psa_dyblocks/dyblock')->load($id);
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
                $model->setData($data);
        }
        else {
            $this->prepareForEdit($model);
        }
        
        Mage::register('dyblock_data', $model);
        
        $this->_addContent($this->getLayout()->createBlock('psa_dyblocks/adminhtml_dyblocks_edit'));
        $this->renderLayout();
    }
    
    public function massDeleteAction()
    {
        // implement mass delete action
        $ids = $this->getRequest()->getParam('dyblock');
        try {
            if (!empty($ids)) {
                $collection = $this->getDyblockCollection($ids);
                $result = $collection->walk('delete');
                $this->_getSession()->addSuccess($this->__("%d Dyblocks deleted.", count($result)));
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
        }
        $this->_redirect('*/*/');
    }
    
    /**
     * @param $ids
     * @return PSA_Ffllocator_Model_Address_Collection
     */
    protected function getDyblockCollection($ids)
    {
        $collection = Mage::getModel('psa_dyblocks/dyblock')->getCollection();
        $collection->addFieldToFilter('dyblock_id', array('in' => $ids));
        return $collection;
    }
    
    
    public function saveAction()
    {
        $req    = $this->getRequest();
        $id     = $req->getParam('id');
        $redirectBack  = $req->getParam('back', false);
        $model  = Mage::getModel('psa_dyblocks/dyblock');
        $data   = $req->getPost();
        
        // If data from form is not good, FAIL!
        if (!$data) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('psa_dyblocks')->__('Unable to find a record to save'));
            $this->_redirect('*/*');
        }
        
        $model->setData($data)->setId($id);
        
        try {
            $this->prepareForSave($model);
            $model->save();
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $msg = Mage::helper('psa_dyblocks')->__('Dynamic Block Saved!');
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
            if ($redirectBack) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
            }
            else {
                $this->_redirect('*/*');
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $id));
        }
        
        return;

    }
    
    
    
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id > 0) {
            try {
                $model = Mage::getModel('psa_dyblocks/dyblock')->load($id);
                $title = $model->getBlockTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Dynamic Block deleted: ' . $title));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }
    
    
    /*
    public function newConditionHtmlAction()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('zblocks/condition'))
            ->setPrefix('conditions');
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }   
     * 
     */ 
    
    
    public function optionsAction()
    {
        $result = '<input id="attr_value" name="attr_value" value="" class="input-text" type="text" />';
        
        $code = $this->getRequest()->getParam('code');
        if (!$code){
            $this->getResponse()->setBody($result);
            return;
        }
        
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($code);
        if (!$attribute){
            $this->getResponse()->setBody($result);
            return;            
        }

        if (!in_array($attribute->getFrontendInput(), array('select', 'multiselect')) ){
            $this->getResponse()->setBody($result);
            return;            
        }
        
        $options = $attribute->getFrontend()->getSelectOptions();
        //array_shift($options);  
        
        $result = '<select id="attr_value" name="attr_value" class="select">';
        foreach ($options as $option){
            $result .= '<option value="'.$option['value'].'">'.$option['label'].'</option>';      
        }
        $result .= '</select>';    
        
        $this->getResponse()->setBody($result);
        
    }
    
  
    protected function prepareForSave($model) {
        $categories = $model->getData('category_ids');
        if (is_array($categories)) {
            $model->setData('category_ids', implode(',', $categories));
        } else {
            $model->setData('category_ids', '');
        }
        return true;
    }
    
    protected function prepareForEdit($model) {
        $categories = $model->getData('category_ids');
        if (!is_array($categories)) {
            $model->setData('category_ids', explode(',', $categories));
        }
        return true;
    }
    
    
}