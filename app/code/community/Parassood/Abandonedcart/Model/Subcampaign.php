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
 */
class Parassood_Abandonedcart_Model_Subcampaign extends Mage_Core_Model_Abstract
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('parassood_abandonedcart/subcampaign');
    }


    /**
     * Get Sales Quotes Part of SubCampaign.
     * @return object
     */
    public function getSubcampaignQuotes()
    {
        $collection = Mage::getModel('sales/quote')->getCollection();
        $_joinCondition = $collection->getConnection()->quoteInto("ABANDONEDCART.quote_id = main_table.entity_id", array());
        $collection->getSelect()->joinLeft(
            array('ABANDONEDCART' => $collection->getTable('parassood_abandonedcart/checkoutstage')),
            $_joinCondition,
            array()
        );

        $collection->getSelect()->where("DATEDIFF(CURDATE(),DATE(main_table.updated_at)) >=" . $this->getOlderThan() .
                                        " AND DATEDIFF(CURDATE(),DATE(main_table.updated_at)) <=" . $this->getYoungerThan() .
                                        " AND ABANDONEDCART.checkout_step = " . $this->getCampaign()->getCheckoutStep() .
                                        " AND main_table.customer_email IS NOT NULL
                                          AND (main_table.store_id = ". $this->getStoreId()." OR 0 = ".$this->getStoreId() . ")");

        $collection->load();
        return $collection;
    }

}