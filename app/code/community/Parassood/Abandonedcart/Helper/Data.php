<?php

class Parassood_Abandonedcart_Helper_Data extends Mage_Core_Helper_Abstract
{
    const AddToCartStage = 1;
    const LoginStage = 2;
    const AddressStage = 3;
    const PaymentStage = 4;
    const OrderPlacedStage = 5;

    public function isAbandonedcartEnabled()
    {
        return Mage::getConfig('parassood_abandonedcart/settings/enabled');
    }

    public function getCheckoutStageOptions()
    {
        $optionsArray = array('1' => 'Cart', '2' => 'Checkout Login', '3' => 'Shipping Address Entered', '4' => 'Payment Method Selected');
        return $optionsArray;
    }

}