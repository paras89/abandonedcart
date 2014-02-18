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

class Parassood_Abandonedcart_Block_Adminhtml_Campaign_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct()
    {
        parent::__construct();
        $this->setId('campaignGrid');
        $this->setUseAjax(false);
        $this->setDefaultSort('campaign_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    /**
     * Prepare related item collection
     *
     * @return Parassood_Abandonedcart_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareCollection()
    {
        $this->_beforePrepareCollection();
        return parent::_prepareCollection();
    }


    protected function _beforePrepareCollection(){
        /* @var $collection Parassood_Abandonedcart_Model_Mysql4_Campaign_Collection */
        $collection = Mage::getModel('parassood_abandonedcart/campaign')->getCollection();
        $this->setCollection($collection);
    }

    /**
     * Prepare columns for Campaign Grid.
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('campaign_id', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Id'),
            'align'     => 'left',
            'width'     => '70',
            'type'      => 'number',
            'index'     => 'campaign_id'
        ));

        $this->addColumn('campaign_name', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Campaign Name'),
            'width'     => '70',
            'type'      => 'text',
            'index'     => 'campaign_name'

        ));

        $this->addColumn('checkout_step', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Checkout Stage'),
            'width'     => '70',
            'type'      => 'options',
            'options'   => Mage::helper('parassood_abandonedcart')-> getCheckoutStageOptions(),
            'index'     => 'checkout_step'
        ));

        $this->addColumn('subcampaign_ids', array(
            'header'    => Mage::helper('parassood_abandonedcart')->__('Sub Campaign Ids'),
            'width'     => '70',
            'type'      => 'text',
            'index'     => 'subcampaign_ids'
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
            'id' => $row->getCampaignId()
        ));
    }
}

?>