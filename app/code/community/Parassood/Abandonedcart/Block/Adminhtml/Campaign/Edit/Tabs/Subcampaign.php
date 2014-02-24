<?php

class Parassood_Abandonedcart_Block_Adminhtml_Campaign_Edit_Tabs_Subcampaign extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('adminform_subcampaign');
        $this->setDefaultSort('subcampaign_id');
        $this->setDefaultDir('ASC');
        // $this->setDefaultFilter(array('in_category'=> 1));
        $this->setUseAjax(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Parassood_Abandonedcart_Block_Adminhtml_Campaign_Edit_Tab_Subcampaign
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('parassood_abandonedcart/subcampaign')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    /**
     * Prepare Columns for the subcampaign grid widget.
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('subcampaign_id', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Id'),
            'align' => 'left',
            'width' => '70',
            'type' => 'number',
            'index' => 'subcampaign_id'
        ));

        $this->addColumn('subcampaign_name', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Sub Campaign Name'),
            'width' => '70',
            'type' => 'text',
            'index' => 'subcampaign_name'

        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('parassood_abandonedcart')->__('Store View'),
                'index' => 'store_id',
                'type' => 'store',
                'width' => '100',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('master_salesrule_id', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Master Sales Rule ID'),
            'width' => '70',
            'type' => 'text',
            'index' => 'master_salesrule_id'
        ));

        $this->addColumn('older_than', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Older Than'),
            'width' => '70',
            'type' => 'text',
            'index' => 'older_than'
        ));

        $this->addColumn('younger_than', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Younger Than'),
            'width' => '70',
            'type' => 'text',
            'index' => 'younger_than'
        ));


        $this->addColumn('enabled', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Status'),
            'index' => 'enabled',
            'width' => '70',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('parassood_abandonedcart')->__('Disabled'),
                1 => Mage::helper('parassood_abandonedcart')->__('Enabled')
            ),
        ));

        $this->addColumn('subcampaign_ids', array(
            'header' => Mage::helper('parassood_abandonedcart')->__('Is Added'),
            'align' => 'left',
            'width' => '70',
            'type' => 'checkbox',
            'field_name' => 'subcampaign_ids[]',
            'index' => 'subcampaign_id',
            'values' => $this->_getSelectedSubcampaigns(),
            'editable' => true,
            'filter' => false

        ));

        return parent::_prepareColumns();
    }


    /**
     * Get Existing Sub Campaigns assigned to this Campaign.
     * @return mixed
     */
    protected function _getSelectedSubcampaigns()
    {
        $id = $this->getRequest()->getParam('id');
        if (!isset($id)) {
            $id = 0;
        }
        if(!Mage::registry('abandonedcart_subcampaignIds')){
        $subcampaignIds = Mage::getModel('parassood_abandonedcart/campaign')->load($id)->getSubcampaignIds();
        $subcampaignIds = explode(',', $subcampaignIds);
        Mage::register('abandonedcart_subcampaignIds',$subcampaignIds);
        }
        return Mage::registry('abandonedcart_subcampaignIds');
    }

    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/subcampaigngrid', array('_current' => true));
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection = $this->getCollection();
        $this->getCollection()->addStoreFilter($value);
    }
}