<?php

class Parassood_Abandonedcart_Helper_Data extends Mage_Core_Helper_Abstract
{
    const AddToCartStage = 1;
    const LoginStage = 2;
    const AddressStage = 3;
    const PaymentStage = 4;
    const OrderPlacedStage = 5;


    /**
     * Is Abandoned Cart Functionality Enabled?
     * @return boolean
     */
    public function isAbandonedcartEnabled()
    {
        return Mage::getStoreConfig('parassood_abandonedcart/settings/enabled');
    }

    /**
     * get Checkout stage option labels
     * @return array
     */
    public function getCheckoutStageOptions()
    {
        $optionsArray = array('1' => 'Cart', '2' => 'Checkout Login', '3' => 'Shipping Address Entered', '4' => 'Payment Method Selected');
        return $optionsArray;
    }

    /**
     * get Sender Name for Abandoned cart campaign emails.
     * @return string
     */
    public function getSenderName()
    {
        return Mage::getStoreConfig('parassood_abandonedcart/settings/sender_name');
    }

    /**
     * get Sender Email Id for Abandoned cart campaign emails.
     * @return string
     */
    public function getSenderEmail()
    {
        return Mage::getStoreConfig('parassood_abandonedcart/settings/sender_email');
    }

}