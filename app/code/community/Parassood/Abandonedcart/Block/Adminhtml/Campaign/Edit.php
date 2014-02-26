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

class Parassood_Abandonedcart_Block_Adminhtml_Campaign_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Variable to store Cause instance
     *
     * @var null|Parassood_Abandonedcart_Model_Campaign
     */
    protected $_campaign = null;

    public function __construct()
    {
        $this->_objectId = 'campaign_id';
        $this->_controller = 'adminhtml_campaign';
        $this->_blockGroup = 'parassood_abandonedcart';
        parent::__construct();


        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        if ($campaign = Mage::registry('current_campaign')) {
            $id = $campaign->getId();
            $this->_addButton('delete', array(
                'label' => Mage::helper('adminhtml')->__('Delete'),
                'onclick' => 'deleteConfirm(\'' . Mage::helper('adminhtml')->__('Are you sure you want to do this?')
                    . '\', \'' . Mage::helper('adminhtml')->getUrl('*/*/delete', array('id' => $id)) . '\')',
                'class' => 'scalable delete',
                'level' => -1
            ));
        }

        $this->_formScripts[] = "function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }";
    }

    /**
     * Get header text for RMA edit page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getCampaign()->getCampaignId()) {
            return Mage::helper('parassood_abandonedcart')
                ->__("Campaign # %s ",
                    intval($this->getCampaign()->getCampaignId()));
        }
    }

    /**
     * Declare region instance
     *
     * @return  Parassood_Abandonedcart_Model_Campaign
     */
    public function getCampaign()
    {
        if (is_null($this->_campaign)) {
            if (!Mage::registry('current_campaign')) {
                $campaign = Mage::getModel('parassood_abandonedcart/campaign');
                Mage::register('current_campaign', $campaign);
            }
            $this->_campaign = Mage::registry('current_campaign');
        }
        return $this->_campaign;
    }
}