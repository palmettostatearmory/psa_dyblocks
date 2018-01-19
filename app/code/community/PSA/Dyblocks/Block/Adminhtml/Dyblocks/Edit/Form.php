<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Block_Adminhtml_Dyblocks_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
    
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                                        'id' => 'edit_form',
                                        'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                        'method' => 'post',
                                        'enctype'=> 'multipart/form-data'
                                     )
        );
        
        $form->setHtmlIdPrefix('dyblock_');

        $hlp = Mage::helper('psa_dyblocks');
        $form->setUseContainer(true);
        $this->setForm($form);
        
        $data = array();
        if (Mage::registry('dyblock_data')) {
            $data = Mage::registry('dyblock_data')->getData();
        }

        
        
        
        
        
        
        $fieldset = $form->addFieldset('dyblock_form', array(
            'legend'=>$hlp->__('Block Info')
        ));

        $fieldset->addField('block_title', 'text', array(
            'name'      => 'block_title',
            'label'     => $hlp->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        
        $fieldset->addField(
            'is_active',
            'select',
            array(
                'name'  => 'is_active',
                'label' => $hlp->__("Enabled?"),
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
                'class'     => 'required-entry',
                'required'  => true
            )
        );
        
        $fieldset->addField('block_position', 'select', array(
            'name'      => 'block_position',
            'label'     => $hlp->__('Position on page'),
            'class'     => 'required-entry',
            'required'  => true,
            'values'    => array(
                'product_notes_top' => $hlp->__('product_notes_top'), 
                'product_block_top' => $hlp->__('product_block_top'), 
                'product_block_bottom' => $hlp->__('product_block_bottom'), 
             ),
        ));
        
        $fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => $hlp->__('Relative position number (lower=higher)'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        
        
        

    
    $fieldset = $form->addFieldset('dyblock_html', array(
            'legend'=>$hlp->__('Content')
        ));
    

    $wysiwyg_config = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array( 'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index') )
        );
    
    $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => $hlp->__('Content'),
            'title'     => $hlp->__('Content'),
            'style'     => 'height:36em',
            'required'  => true,
            'config'    => $wysiwyg_config,
            'wysiwyg'   => true
        ));
    
    
        $fieldset = $form->addFieldset('conditions_form', array(
            'legend'=>$hlp->__('Conditions (All AND conditions. If any fails, block will not show.)')
        ));
    
    $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            
        $fieldset->addField('from_date', 'date', array(
            'name'      => 'from_date',
            'label'     => $hlp->__('Start Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' =>  Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => false,
        ));
        
        $fieldset->addField('to_date', 'date', array(
            'name'      => 'to_date',
            'label'     => $hlp->__('End Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' =>  Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'required'  => false,
        ));
        
        
    $fieldset->addField('include_skus', 'text', array(
        'label'     => $hlp->__('SKUs'),
        'name'      => 'include_skus',
        'note'      => $hlp->__('Comma separated SKUs, no spaces.'),
    ));
        
        
    $fieldset->addField('attr_code', 'select', array(
            'label'     => $hlp->__('Has attribute'),
            'name'      => 'attr_code',
            'values'    => $this->getAttributes(),
            'onchange'  => 'showOptions(this)',
            'note'      => $hlp->__('If you do not see the label, please make sure the attribute properties `Visible on Product View Page on Front-end`, `Used in Product Listing` are set to `Yes`'),
        ));
    
    if(isset($data['attr_code'])){
        $attributeCode = $data['attr_code'];
    } else {
        $attributeCode = '';
    }
    
    if (($attributeCode && '' != $attributeCode) && ($attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($attributeCode))) {

        if ('select' === $attribute->getFrontendInput() || 'multiselect' === $attribute->getFrontendInput()) {
            $options = $attribute->getFrontend()->getSelectOptions();
            $fieldset->addField('attr_value', 'select', array(
              'label'     => $hlp->__('Attribute value is'),
              'name'      => 'attr_value',
              'values'    => $options,
            ));
        } else {
            $fieldset->addField('attr_value', 'text', array(
              'label'     => $hlp->__('Attribute value is'),
              'name'      => 'attr_value',
            ));
        }
    } else {
        $fieldset->addField('attr_value', 'text', array(
            'label'     => $hlp->__('Attribute value is'),
            'name'      => 'attr_value',
        ));
    }
    
    
    $fieldset->addField('checkbox', 'checkbox', array(
          'label'     => $hlp->__('Toggle Categories'),
          'name'      => 'toggle',
          'checked' => false,
          'onclick' => "switchcheck(this)",
          'disabled' => false,
        ));
    
    
    
    $fieldset->addField('category_ids', 'checkboxes', array(
        'label'     => $hlp->__('Categories'),
        'name'      => 'category_ids[]',
        'onclick' => "",
        'onchange' => "",
        'disabled' => false,
        'tabindex' => 1,
        'values'    => $this->getTree(),
    ));
    
        
    
        
        
        
        
        
        Mage::dispatchEvent('adminhtml_edit_prepare_form', array('block'=>$this, 'form'=>$form));

        if (Mage::registry('dyblock_data')) {
            if (isset($data['data_serialized']) && !empty($data['data_serialized'])) {
                $d2 = json_decode($data['data_serialized'], true);
                if ( !empty($d2) ) {
                    $data = array_merge( $data, $d2 );
                }
//                var_dump($data);
            }
            $form->setValues($data);
        }
        
        return parent::_prepareForm();
    }
    
    
    
    protected function getAttributes()
    {
        $collection = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setItemObjectClass('catalog/resource_eav_attribute')
            ->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getTypeId())
        ;
            
        $options = array(''=>'');
		foreach ($collection as $attribute){
		    $label = $attribute->getFrontendLabel();
			if ($label){ // skip system attributes
			    $options[$attribute->getAttributeCode()] = $label;
			}
		}
		asort($options);
        
		return $options;
    }
    
    
    
    /**
     * Genarates tree of all categories
     *
     * @return array sorted list category_id=>title
     */
    protected function getTree()
    {
        $rootId = Mage::app()->getStore(0)->getRootCategoryId();         
        $tree = array();
        
        $collection = Mage::getModel('catalog/category')
            ->getCollection()->addNameToResult();
        
        $pos = array();
        foreach ($collection as $cat){
            $path = explode('/', $cat->getPath());
            if ((!$rootId || in_array($rootId, $path)) && $cat->getLevel()){
                $tree[$cat->getId()] = array(
                    'label' => str_repeat('--', $cat->getLevel()) . $cat->getName(), 
                    'value' => $cat->getId(),
                    'path'  => $path,
                );
            }
            $pos[$cat->getId()] = $cat->getPosition();
        }
        
        foreach ($tree as $catId => $cat){
            $order = array();
            foreach ($cat['path'] as $id){
            	if (isset($pos[$id])){
                	$order[] = $pos[$id];
            	}
            }
            $tree[$catId]['order'] = $order;
        }
        
        usort($tree, array($this, 'compare'));
        //array_unshift($tree, array('value'=>'', 'label'=>''));
        
        return $tree;
    }
    
    /**
     * Compares category data. Must be public as used as a callback value
     *
     * @param array $a
     * @param array $b
     * @return int 0, 1 , or -1
     */
    public function compare($a, $b)
    {
        foreach ($a['path'] as $i => $id){
            if (!isset($b['path'][$i])){ 
                // B path is shorther then A, and values before were equal
                return 1;
            }
            if ($id != $b['path'][$i]){
                // compare category positions at the same level
                $p = isset($a['order'][$i]) ? $a['order'][$i] : 0;
                $p2 = isset($b['order'][$i]) ? $b['order'][$i] : 0;
                return ($p < $p2) ? -1 : 1;
            }
        }
        // B path is longer or equal then A, and values before were equal
        return ($a['value'] == $b['value']) ? 0 : -1;
    }   
    
    
}