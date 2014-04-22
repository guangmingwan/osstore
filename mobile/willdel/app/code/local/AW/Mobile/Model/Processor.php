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


class AW_Mobile_Model_Processor extends Enterprise_PageCache_Model_Processor
{

    public function __construct()
    {
        parent::__construct();

        if (isset($_COOKIE[AW_Mobile_Model_Observer::MOBILE_COOKIE])) {
            $this->_requestId .= "_" . $_COOKIE[AW_Mobile_Model_Observer::MOBILE_COOKIE];
        } else {
            $this->_requestId .= "_" . time() . mt_rand();
        }
        if (preg_match("#switch/toMobile#", $this->_requestId) || (preg_match("#switch/toDesktop#", $this->_requestId))) {
            $this->_requestId .= "_" . time() . mt_rand();
        }

        Mage::register('_singleton/enterprise_pagecache/processor', $this, true);

        $this->_requestCacheId = $this->prepareCacheId($this->_requestId);
    }

}

