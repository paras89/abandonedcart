<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Parassood
 * @package     Parassood_Abandonedcart
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/

class Parassood_Abandonedcart_Block_Adminhtml_Subcampaign_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct()
    {
        parent::__construct();
        $this->setId('subcampaignGrid');
        $this->setUseAjax(false);
        $this->setDefaultSort('subcampaign_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    /**
     * Prepare related item collection
     *
     * @return Parassood_Abandonedcart_Block_Adminhtml_Subcampaign_Grid
     */
    protected function _prepareCollection()
    {
        $this->_beforePrepareCollection();
        return parent::_prepareCollection();
    }

    protected function _beforePrepareCollection(){
        /* @var $collection Parassood_Abandonedcart_Model_Mysql4_Campaign_Collection */
        $collection = Mage::getModel('parassood_abandonedcart/subcampaign')->getCollection();
        $this->setCollection($collection);
    }
    protected function _prepareColumns()
    {
        $this->addColumn('subcampaign_id', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Id'),
            'align'     => 'left',
            'width'     => '70',
            'type'      => 'number',
            'index'     => 'subcampaign_id'
        ));

        $this->addColumn('subcampaign_name', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Sub Campaign Name'),
            'width'     => '70',
            'type'      => 'text',
            'index'     => 'subcampaign_name'

        ));


        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('parassood_abandonedcart')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'width'         => '100',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('master_salesrule_id', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Master Sales Rule ID'),
            'width'     => '70',
            'type'      => 'text',
            'index'     => 'master_salesrule_id'
        ));

        $this->addColumn('older_than', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Older Than'),
            'width'     => '70',
            'type'      => 'text',
            'index'     => 'older_than'
        ));

        $this->addColumn('younger_than', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Younger Than'),
            'width'     => '70',
            'type'      => 'test',
            'index'     => 'younger_than'
        ));



        $this->addColumn('enabled', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Status'),
            'index'     => 'enabled',
            'width'     => '70',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('parassood_abandonedcart')->__('Disabled'),
                1 => Mage::helper('parassood_abandonedcart')->__('Enabled')
            ),
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Created At'),
            'width'     => '70',
            'type'      => 'datetime',
            'index'     => 'created_at'
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Updated At'),
            'width'     => '70',
            'type'      => 'datetime',
            'index'     => 'updated_at'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Retrieve row url
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $row->getSubcampaignId()
        ));
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }
}

?>