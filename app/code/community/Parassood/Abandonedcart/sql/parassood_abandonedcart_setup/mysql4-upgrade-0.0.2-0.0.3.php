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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('parassood_abandonedcart/tracking'))

    ->addColumn(
        'id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'ID')
    ->addColumn(
        'campaign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'Campaign ID')
    ->addColumn(
        'subcampaign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'Sub Campaign ID')
    ->addColumn(
        'triggered_on',
        Varien_Db_Ddl_Table::TYPE_DATE,
        null,
        array(
            'unsigned' => true,
        ),
        'Date Mailing Triggered on')
    ->addColumn(
        'emails_sent',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
        ),
        'Total Emails sent')
    ->addColumn(
        'emails_opened',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
        ),
        'Total Emails Opened')
    ->addColumn(
        'order_success',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
        ),
        'Total Orders Placed');

$installer->getConnection()->createTable($table);

$installer->endSetup();
