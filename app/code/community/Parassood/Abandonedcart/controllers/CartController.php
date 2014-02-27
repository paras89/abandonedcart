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
class Parassood_Abandonedcart_CartController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $info = unserialize(urldecode($this->getRequest()->getParam('info')));
        $info = $this->_sanitizeInfo($info);
        $quote = Mage::getModel('sales/quote')->load($info['id']);
        if ($quote->getId()) {
            $cart = Mage::getSingleton('checkout/cart');
            $cart->setQuote($quote);
            $cart->save();
            $tracking = Mage::getModel('parassood_abandonedcart/tracking')->load($info['cmpn']);
            if ($tracking->getId()) {
                $tracking->setEmailsOpened($tracking->getEmailsOpened() + 1);
                Mage::getSingleton('checkout/session')->setData('abdcart_tracking_id', $tracking->getId());
                $tracking->save();
            }
            $this->_redirect('checkout/cart');
        }
        $this->_redirect(Mage::getBaseUrl());
    }

    /**
     * Add necessary keys to info array.
     * @param $info
     * @return array
     */
    protected function _sanitizeInfo($info)
    {
        if (!is_array($info)) {
            return array('cmpn' => '', 'id' => '');
        }

        $keys = array('id', 'cmpn');
        foreach ($keys as $key) {
            if (!array_key_exists($key, $info)) {
                $info[$key] = '';
            }
        }
        return $info;
    }


}
