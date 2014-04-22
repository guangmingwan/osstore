<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
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
 * @package    AW_Collpur
 * @version    1.0.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */

class AW_Collpur_Model_Source_Progress
{
    const PROGRESS_NOT_RUNNING = 'not_running';
    const PROGRESS_RUNNING = 'running';
    const PROGRESS_EXPIRED = 'expired';
    const PROGRESS_CLOSED = 'closed';
    const PROGRESS_ARCHIVED = 'archived';
    
    public function toOptionArray()
    {
        return array(
            self::PROGRESS_NOT_RUNNING => Mage::helper('collpur')->__('Not Running'),
            self::PROGRESS_RUNNING => Mage::helper('collpur')->__('Running'),
            self::PROGRESS_EXPIRED => Mage::helper('collpur')->__('Expired'),
            self::PROGRESS_CLOSED => Mage::helper('collpur')->__('Closed'),
            self::PROGRESS_ARCHIVED => Mage::helper('collpur')->__('Archived'),
        );
    }
}