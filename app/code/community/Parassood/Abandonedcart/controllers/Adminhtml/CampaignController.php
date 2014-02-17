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
class Parassood_Abandonedcart_Adminhtml_CampaignController extends Mage_Adminhtml_Controller_Action
{


    /**
     * Iniitializes page layout
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('parassood_abandonedcart/campaign')
            ->_addBreadcrumb($this->__('Campaign Management'), $this->__('Campaign Management'));
        return $this;
    }

    public function indexAction()
    {

        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->editAction();
    }

    /**
     * Edit Campaign action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id',false);
        if ($id) {
            $this->_title($this->__('Edit Abandoned Cart Campaign'));
            $campaign = Mage::getModel('parassood_abandonedcart/campaign')->load($id);
            if (!$campaign->getCampaignId()) {
                Mage::throwException($this->__('Wrong Campaign requested.'));
            }
            Mage::register('current_region', $campaign);
            Mage::getSingleton('adminhtml/session')->setCampaignData($campaign->getData());

        } else {
            $this->_title($this->__('Create a new Abandoned Cart Campaign'));
        }
        $this->_initAction();
        $this->renderLayout();

    }

    /**
     * Save Adandoned Cart Campaign
     *
     * @throws Mage_Core_Exception
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            array_walk_recursive($data, array($this, 'trimFormWhitespace'));
            $campaign = Mage::getModel('parassood_abandonedcart/campaign');
            if (empty($data['campaign_id'])) {
                unset($data['campaign_id']);
                $campaign->setCreatedAt(now());
            }else{
                $campaign->load($data['campaign_id']);
            }
            try {

                $campaign->setUpdatedAt(now())
                    ->setSubcampaignIds($data['subcampaign_ids'])
                    ->setCheckoutStep($data['checkout_step'])
                    ->setCampaignName($data['campaign_name'])
                    ->save();

            } catch (Exception $e) {

                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/');
                return;
            }

            $this->_getSession()->addSuccess('Form Saved');
        }
        $this->_redirect('*/*/');
    }

    public function trimFormWhitespace($item, $key)
    {
        $item = trim($item);
    }
}