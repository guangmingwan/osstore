<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Mobile
 * @version    1.6.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

$installer = $this;
$installer->startSetup();

# Create example footer lincs static block
try {
    $block = Mage::getModel('cms/block');
    $data = array(

        'title' => 'Mobile Footer Links (Example)',
        'identifier' => 'mobile_footer_links',
        'content' => '<div class="foooter_menu">
                                <span class="item first"><a title="About" href="{{store direct_url="about-magento-demo-store"}}">About</a></span>
                    <span class="sep">|</span>                            <span class="item"><a title="Customer Service" href="{{store direct_url="customer-service"}}">Customer Service</a></span>
                    <span class="sep">|</span>                            <span class="item last"><a title="Contact" href="{{store direct_url="contacts"}}">Contact</a></span>
                                        </div>',
        'is_active' => 1,
    );

    $block->addData($data);
    $block->save();
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();

