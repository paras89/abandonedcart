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
class Parassood_Abandonedcart_Model_Observer
{

    /**
     * Set the customer at add to cart step of checkout.
     * @param Varien_Event_Observer $observer
     */
    public function setCartStage($observer)
    {
        $quote = $observer->getQuote();
        $stage = 0;
        $addressEmail = $quote->getShippingAddress()->getEmail();
        $paymentMethod = $quote->getPayment()->getMethod();
        $quoteId = $quote->getId();
        $abandonedCart = Mage::getModel('parassood_abandonedcart/checkoutstage')->load($quoteId, 'quote_id');
        $abandonedCartId = $abandonedCart->getId();
        $checkoutMethod = $quote->getCheckoutMethod();
        if (!isset($abandonedCartId)) {
            $stage = Parassood_Abandonedcart_Helper_Data::AddToCartStage;
            $abandonedCart->setQuoteId($quoteId);
        }
        if(isset($checkoutMethod) && $checkoutMethod != '')
        {
            $stage = Parassood_Abandonedcart_Helper_Data::LoginStage;
        }
        if (isset($addressEmail)) {
            $stage = Parassood_Abandonedcart_Helper_Data::AddressStage;
        }
        if (isset($paymentMethod)) {
            $stage = Parassood_Abandonedcart_Helper_Data::PaymentStage;
        }
        if ($stage > $abandonedCart->getCheckoutStep() || !isset($abandonedCartId)) {
            $abandonedCart->setCheckoutStep($stage);
            $abandonedCart->save();
        }
        return;
    }

    /**
     * Set Abandon Cart to ordered step.
     * @param Varient_Event_Obsever $observer
     */
    public function setOrderedStage($observer)
    {
        $quoteId = $observer->getQuote()->getId();
        $abandonedCart = Mage::getModel('parassood_abandonedcart/checkoutstage')->load($quoteId, 'quote_id');
        $abandonedCartId = $abandonedCart->getId();
        if(isset($abandonedCartId))
        {
            $abandonedCart->setCheckoutStep(Parassood_Abandonedcart_Helper_Data::OrderPlacedStage);
            $abandonedCart->save();
        }
        return;
    }

    public function runAbandonedCartCampaign()
    {
        $abandonedCartModel = Mage::getModel('parassood_abandonedcart/cart');
    }

    public function sendAbandonedCartEmail()
    {
        $emailTemplate  = Mage::getModel('core/email_template');
        $emailTemplate->loadDefault('custom_abandonedcart_email');
        $emailTemplate->setTemplateSubject('Your Purchase is pending!');

        // Get General email address (Admin->Configuration->General->Store Email Addresses)
        $salesData['email'] = "parassoo@deloitte.com";//Mage::getStoreConfig('trans_email/ident_general/email');
        $salesData['name'] = "Paras Sood";//Mage::getStoreConfig('trans_email/ident_general/name');

        $emailTemplate->setSenderName($salesData['name']);
        $emailTemplate->setSenderEmail($salesData['email']);

        $emailTemplateVariables['username']  = 'test customer';//$order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        $emailTemplateVariables['order_id'] = 'cart';//$order->getIncrementId();
        $emailTemplateVariables['store_name'] = 'collection'; //$order->getStoreName();
        $emailTemplateVariables['store_url'] = 'dstoeruss'; //Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $emailTemplate->send('parssoodass@deooitte.com', 'sotename', $emailTemplateVariables);
    }

}
