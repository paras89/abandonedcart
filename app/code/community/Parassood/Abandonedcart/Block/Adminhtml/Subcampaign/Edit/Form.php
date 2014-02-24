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
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software Licensea (OSL 3.0)
 **/
class Parassood_Abandonedcart_Block_Adminhtml_Subcampaign_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{


    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ));

        $helper = Mage::helper('parassood_abandonedcart');
        $form->setUseContainer(true);
        $this->setForm($form);
        $fieldset = $form->addFieldset('edit_subcampaign_form', array('legend' => $helper->__('Edit Sub Campaign')));

        $fieldset->addField('subcampaign_id', 'hidden', array(
            'name' => 'subcampaign_id',
        ));

        $fieldset->addField('subcampaign_name', 'text', array(
            'label' => $helper->__('Sub Campaign Name'),
            'name' => 'subcampaign_name',
            'required' => true,
            'maxlength' => 80,
        ));

        $fieldset->addField('master_salesrule_id', 'text', array(
            'label' => $helper->__('Master Sales Rule ID'),
            'name' => 'master_salesrule_id',
            'required' => false,
        ));

        $fieldset->addField('older_than', 'text', array(
            'label' => $helper->__('Target Quotes Older Than'),
            'name' => 'older_than',
            'required' => true
        ));

        $fieldset->addField('younger_than', 'text', array(
            'label' => $helper->__('Target Quotes Younger Than'),
            'name' => 'younger_than',
            'required' => true
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'store_id',
                'label' => 'Store View',
                'title' => 'Store View',
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }

        $fieldset->addField('enabled', 'select', array(
            'name' => 'enabled',
            'label' => 'Sub Campaign Status',
            'title' => 'Sub Campaign Status',
            'required' => true,
            'values' => array(
                0 => Mage::helper('parassood_abandonedcart')->__('Disabled'),
                1 => Mage::helper('parassood_abandonedcart')->__('Enabled')
            ),
        ));

        if (Mage::registry('current_subcampaign')) {
            $form->setValues(Mage::registry('current_subcampaign')->getData());
        }
        return parent::_prepareForm();
    }
}