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
class Parassood_Abandonedcart_Block_Adminhtml_Campaign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('parassood_abandonedcart')->__('Abandoned Cart Campaigns'));
    }

    protected function _beforeToHtml()
    {

        $this->addTab('campaign_section', array(
            'label' => Mage::helper('parassood_abandonedcart')->__('Campaign Settings'),
            'title' => Mage::helper('parassood_abandonedcart')->__('Campaign Settings'),
            'content' => $this->getLayout()
                    ->createBlock('parassood_abandonedcart/adminhtml_campaign_edit_tabs_campaign')
                    ->toHtml(),
        ));

        $this->addTab('subcampaign_section', array(
            'label' => Mage::helper('parassood_abandonedcart')->__('Subcampaign Settings'),
            'title' => Mage::helper('parassood_abandonedcart')->__('Subcampaign Settings'),
            'url' => $this->getUrl('*/*/subcampaign', array('_current' => true)), 'class' => 'ajax',
        ));

        return parent::_beforeToHtml();
    }
}