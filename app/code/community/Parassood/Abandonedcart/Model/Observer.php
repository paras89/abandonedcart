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
        $quoteId = $observer->getQuote()->getId();
        $abandonedCart = Mage::getModel('parassood_abandonedcart/checkoutstage')->load($quoteId, 'quote_id');
        $abandonedCartId = $abandonedCart->getId();
        if (isset($abandonedCartId)) {
            $abandonedCart->setCheckoutStep(Parassood_Abandonedcart_Helper_Data::OrderPlacedStage);
            $abandonedCart->save();
        }
        return;
    }

    /**
     * Cron to Send Abandoned Cart E-Mails.
     */
    public function sendAbandonedCartEmail()
    {
        $campaign = Mage::getModel('parassood_abandonedcart/campaign');
        $campaignCollection = $campaign->getCollection()->load();
        $subCampaign = Mage::getModel('parassood_abandonedcart/subcampaign');
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->loadDefault('custom_abandonedcart_email');
        $emailTemplate->setTemplateSubject('Your Purchase is pending!');

        foreach ($campaignCollection as $campaign) {

            $subcampaignIds = explode(',', $campaign->getSubcampaignIds());
            foreach ($subcampaignIds as $subcampaignId) {
                $subCampaign->load($subcampaignId);
                if (!$subCampaign->getEnabled()) {
                    continue;
                }

                $subCampaign->setCampaign($campaign);
                $quotes = $subCampaign->getSubcampaignQuotes();
                foreach ($quotes as $quote) {

                    $emailTemplate->setSenderName('paras');
                    $emailTemplate->setSenderEmail('paras@parassood.com');
                    $emailTemplateVariables['username'] = $quote->getCustomerFirstname();
                    $params = array('id' => $quote->getId(),'campaign_id'=> $campaign->getCampaignId(),'subcampaign_id' => $subCampaign->getSubcampaignId(),'date' => time());
                    $params = urlencode(serialize($params));
                    $emailTemplateVariables['cart_url'] = Mage::getUrl('recreate/cart/',array('info' => $params));
                    $emailTemplateVariables['promocode'] = $this->_generateSalesRule($subCampaign, $quote);
                    $emailTemplate->send($quote->getCustomerEmail(), 'store', $emailTemplateVariables);

                }
            }
        }

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
