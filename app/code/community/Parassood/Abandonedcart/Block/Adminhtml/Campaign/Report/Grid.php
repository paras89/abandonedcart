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

class Parassood_Abandonedcart_Block_Adminhtml_Campaign_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('gridSimple');
    }

    protected function _prepareCollection()
    {
        $this->_beforePrepareCollection();
        parent::_prepareCollection();


    }

    protected function _beforePrepareCollection()
    {
        /* @var $collection Parassood_Abandonedcart_Model_Mysql4_Tracking_Collection */
        $collection = Mage::getModel('parassood_abandonedcart/tracking')->getCollection();
        $this->setCollection($collection);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('ID'),
            'index'     =>'id',
            'sortable'  => false,

        ));

        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('campaign_id', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Campaign ID'),
            'index'     =>'campaign_id',
            'type'      =>'text',

        ));

        $this->addColumn('subcampaign_id', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Sub Campaign ID'),
            'index'     =>'subcampaign_id',
            'type'      =>'text',

        ));

        $this->addColumn('triggered_on', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Email Triggered On'),
            'index'     =>'triggered_on',
            'type'      =>'date',

        ));

        $this->addColumn('emails_sent', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Emails Triggered'),
            'index'     =>'emails_sent',
            'type'      =>'text',

        ));

        $this->addColumn('emails_opened', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Emails Opened'),
            'index'     =>'emails_opened',
            'type'      =>'text',

        ));

        $this->addColumn('order_success', array(
            'header'    =>Mage::helper('parassood_abandonedcart')->__('Emails Conversions'),
            'index'     =>'order_success',
            'type'      =>'text',

        ));

        $this->addExportType('*/*/exportSimpleCsv', Mage::helper('reports')->__('CSV'));

        $this->setFilterVisibility(false);
        return parent::_prepareColumns();
    }

}