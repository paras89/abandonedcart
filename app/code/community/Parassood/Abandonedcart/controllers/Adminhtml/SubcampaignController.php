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
class Parassood_Abandonedcart_Adminhtml_SubcampaignController extends Mage_Adminhtml_Controller_Action
{


    /**
     * Iniitializes page layout
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('parassood_abandonedcart/subcampaign')
            ->_addBreadcrumb($this->__('Sub Campaign Management'), $this->__('Sub Campaign Management'));
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
     * Edit Sub Campaign action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id',false);
        if ($id) {
            $this->_title($this->__('Edit Abandoned Cart Sub Campaign'));
            $subcampaign = Mage::getModel('parassood_abandonedcart/subcampaign')->load($id);
            if (!$subcampaign->getSubcampaignId()) {
                Mage::throwException($this->__('Wrong Sub Campaign requested.'));
            }
            Mage::register('current_subcampaign', $subcampaign);
            Mage::getSingleton('adminhtml/session')->setSubcampaignData($subcampaign->getData());

        } else {
            $this->_title($this->__('Create a new Abandoned Cart Sub Campaign'));
        }
        $this->_initAction();
        $this->renderLayout();

    }

    /**
     * Save Adandoned Cart Sub Campaign
     *
     * @throws Mage_Core_Exception
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            array_walk_recursive($data, array($this, 'trimFormWhitespace'));
            $subcampaign = Mage::getModel('parassood_abandonedcart/subcampaign');
            if (empty($data['subcampaign_id'])) {
                unset($data['subcampaign_id']);
                $subcampaign->setCreatedAt(now());
            }else{
                $subcampaign->load($data['subcampaign_id']);
            }
            try {

                $subcampaign->setUpdatedAt(now())
                    ->setMasterSalesruleId($data['master_salesrule_id'])
                    ->setStoreId($data['store_id'])
                    ->setSubcampaignName($data['subcampaign_name'])
                    ->setEnabled($data['enabled'])
                    ->setOlderThan($data['older_than'])
                    ->setYoungerThan($data['younger_than'])
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