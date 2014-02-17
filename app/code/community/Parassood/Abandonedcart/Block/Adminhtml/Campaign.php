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

class Parassood_Abandonedcart_Block_Adminhtml_Campaign extends Mage_Adminhtml_Block_Widget_Grid_Container{

    /**
     * Initialize Campaign management page
     *
     * @return void
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_campaign';
        $this->_blockGroup = 'parassood_abandonedcart';
        $this->_headerText = Mage::helper('parassood_abandonedcart')->__('Abandoned Cart - Manage Campaigns');
        parent::__construct();
    }
}