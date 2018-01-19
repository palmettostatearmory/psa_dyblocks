<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PSA_Dyblocks_Block_Adminhtml_Dyblocks_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('dyblocksGrid');
        $this->setDefaultSort('dyblock_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('psa_dyblocks/dyblock')->getCollection());

        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('dyblock_id', array(
                                             'header' => Mage::helper('psa_dyblocks')->__('ID'),
                                             'align'  => 'right',
                                             'width'  => '50px',
                                             'index'  => 'dyblock_id',
                                             'type'   => 'number',
                                        ));

        $this->addColumn('block_title', array(
                                       'header' => Mage::helper('psa_dyblocks')->__('Title'),
                                       'align'  => 'left',
                                       'index'  => 'block_title',
                                  ));
        
        $this->addColumn('is_active', array(
                                       'header' => Mage::helper('psa_dyblocks')->__('Status'),
                                       'align'  => 'left',
                                       'index'  => 'is_active',
                                       'type'    => 'options',
                                       'options' => array(
                                            0 => Mage::helper('psa_dyblocks')->__('Disabled'),
                                            1 => Mage::helper('psa_dyblocks')->__('Enabled')
                                        ),
                                  ));
        
        $this->addColumn('from_date', array(
                'header' => Mage::helper('psa_dyblocks')->__('Date From'),
                'index'  => 'from_date',
                'type'   => 'date',
                'width'  => '100px',
        ));

        $this->addColumn('to_date', array(
                'header' => Mage::helper('psa_dyblocks')->__('Date To'),
                'index'  => 'to_date',
                'type'   => 'date',
                'width'  => '100px',
        ));
        


        Mage::dispatchEvent('psa_dyblocks_adminhtml_grid_prepare_columns', array('block' => $this));

        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('dyblock_id');
        $block = $this->getMassactionBlock();
        $block->setFormFieldName('dyblock');

        $block->addItem('delete', array(
                        'label'   => Mage::helper('psa_dyblocks')->__('Delete'),
                        'url'     => $this->getUrl('*/*/massDelete'),
                        'confirm' => Mage::helper('psa_dyblocks')->__('Are you sure?')
       ));
        return $this;
    }
    
}