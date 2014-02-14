<?php

class Parassood_Abandonedcart_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function isAbandonedcartEnabled()
    {
        return Mage::getConfig('parassood_abandonedcart/settings/enabled');
    }

}