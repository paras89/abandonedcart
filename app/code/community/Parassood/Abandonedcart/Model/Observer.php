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
        if(!Mage::helper('parassood_abandonedcart')->isAbandonedcartEnabled())
            return;
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
        if (isset($checkoutMethod) && $checkoutMethod != '') {
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
        if(!Mage::helper('parassood_abandonedcart')->isAbandonedcartEnabled())
            return;
        $quoteId = $observer->getQuote()->getId();
        $abandonedCart = Mage::getModel('parassood_abandonedcart/checkoutstage')->load($quoteId, 'quote_id');
        $abandonedCartId = $abandonedCart->getId();
        if (isset($abandonedCartId)) {
            $abandonedCart->setCheckoutStep(Parassood_Abandonedcart_Helper_Data::OrderPlacedStage);
            $abandonedCart->save();
        }
        $trackingId = Mage::getSingleton('checkout/session')->getAbdcartTrackingId();
        if(isset($trackingId)){
            $tracking = Mage::getModel('parassood_abandonedcart/tracking')->load($trackingId);
            $tracking->setOrderSuccess($tracking->getOrderSuccess() + 1);
            $tracking->save();
        }
        return;
    }

    /**
     * Cron to Send Abandoned Cart E-Mails.
     */
    public function sendAbandonedCartEmail()
    {
        if(!Mage::helper('parassood_abandonedcart')->isAbandonedcartEnabled())
            return;
        $campaign = Mage::getModel('parassood_abandonedcart/campaign');
        $campaignCollection = $campaign->getCollection()->load();
        $subCampaign = Mage::getModel('parassood_abandonedcart/subcampaign');
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->setTemplateSubject('Your Purchase is pending!');
        $tracking = Mage::getModel('parassood_abandonedcart/tracking');
        $tracking->setTriggeredOn(date('d-m-Y',time()));
        $senderName = Mage::helper('parassood_abandonedcart')->getSenderName();
        $senderEmail = Mage::helper('parassood_abandonedcart')->getSenderEmail();
        $emailTemplate->setSenderName($senderName);
        $emailTemplate->setSenderEmail($senderEmail);


        foreach ($campaignCollection as $campaign) {

            $tracking->setCampaignId($campaign->getCampaignId());
            $subcampaignIds = explode(',', $campaign->getSubcampaignIds());
            foreach ($subcampaignIds as $subcampaignId) {
                $tracking->setSubcampaignId($subcampaignId);
                $subCampaign->load($subcampaignId);
                if (!$subCampaign->getEnabled()) {
                    continue;
                }
                $skipPromo = false;
                if($subCampaign->getMasterSalesruleId() == 0){
                    $emailTemplate->loadDefault('custom_abandonedcart_reminder_email');
                    $skipPromo = true;
                }else{
                    $emailTemplate->loadDefault('custom_abandonedcart_email');
                }

                $subCampaign->setCampaign($campaign);
                $quotes = $subCampaign->getSubcampaignQuotes();
                $quoteCount = 0;
                $tracking->save();
                foreach ($quotes as $quote) {

                    $emailTemplateVariables['username'] = $quote->getCustomerFirstname();
                    $params = array('cmpn' => $tracking->getId(),'id' => $quote->getEntityId());
                    $params = urlencode(serialize($params));
                    $emailTemplateVariables['cart_url'] = Mage::getUrl('recreate/cart/',array('info' => $params));
                    if(!$skipPromo){
                        $emailTemplateVariables['promocode'] = $this->_generateSalesRule($subCampaign, $quote);
                    }
                    $emailTemplate->send($quote->getCustomerEmail(), 'store', $emailTemplateVariables);
                    $quoteCount++;
                }
                $tracking->setEmailsSent($quoteCount)
                         ->setEmailsOpened(0)
                         ->setOrderSuccess(0)
                         ->save();

                $tracking->setId(null);
            }
        }
        return;
    }


    /**
     * Create Promo Code/Sales Rule for a particular quote.
     * @param $subCampaign
     * @param $quote
     * @return null
     */
    protected function _generateSalesRule($subCampaign, $quote)
    {
        $masterRule = Mage::getModel('salesrule/rule')->load($subCampaign->getMasterSalesruleId());
        if (!$masterRule->getId() || !$subCampaign->getEnabled()) {
            return null;
        }
        $masterRule->setId(null)
            ->save();
        $couponCode = Mage::getModel('salesrule/coupon');
        $couponCode->setRuleId($masterRule->getId())
            ->setCode("XYPE" . $masterRule->getRuleId() . $quote->getCustomerEmail())
            ->setIsPrimary(1)
            ->save();
        return $couponCode->getCode();
    }

}
