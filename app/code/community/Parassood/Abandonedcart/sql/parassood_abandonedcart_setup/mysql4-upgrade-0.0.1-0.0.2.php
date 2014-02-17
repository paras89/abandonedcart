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
$table = $installer->getConnection()->newTable($installer->getTable('parassood_abandonedcart/campaign'))
    ->addColumn(
        'campaign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'Campaign ID')
    ->addColumn(
        'subcampaign_ids',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(

            'unsigned' => true,
            'nullable' => false

        ),
        'Sub Campaign ID')
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(

            'unsigned' => true,
            'nullable' => false

        ),
        'Created At')
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(

            'unsigned' => true,
            'nullable' => false

        ),
        'Updated At')
    ->addColumn(
        'checkout_step',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Checkout Step')
    ->addColumn(
        'campaign_name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Campaign Name');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()->newTable($installer->getTable('parassood_abandonedcart/subcampaign'))
    ->addColumn(
        'subcampaign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'Sub Campaign ID')
    ->addColumn(
        'subcampaign_name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(
            'nullable' => false,
        ),
        'Sub Campaign Name')
    ->addColumn(
        'master_salesrule_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false

        ),
        'Sub Campaign ID')
    ->addColumn(
        'older_than',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Older than')
    ->addColumn(
        'younger_than',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Younger Than')
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Store ID')
    ->addColumn(
        'enabled',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(

            'unsigned' => true,
            'nullable' => false,

        ),
        'Enabled')
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(
            'unsigned' => true,
            'nullable' => false
        ),
        'Created At')
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(

            'unsigned' => true,
            'nullable' => false

        ),
        'Updated At');
$installer->getConnection()->createTable($table);
$installer->endSetup();
