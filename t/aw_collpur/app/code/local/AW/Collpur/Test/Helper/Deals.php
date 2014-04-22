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


class AW_Collpur_Test_Helper_Deals extends EcomDev_PHPUnit_Test_Case {

    /**
     * Core function returning timeDiff
     * Use gmmktime and gmdate to generate mock timestamps
     *
     * @dataProvider provider__testGetTimeLeftToBuy
     * 
     */
    public function testGetTimeLeftToBuy($data) {

        $this->assertEquals(
                Mage::helper('collpur/deals')->getTimeLeftToBuy(false, false, $data['from'], $data['to']),
                '1 day 0:00');
    }

    public function provider__testGetTimeLeftToBuy() {

        return array(
            array(
                array('from' => '0', 'to' => '86400', 'result' => '1 day 0:00', 'calculated' => true)
            )
        );
    }

    /**
     * @test
     */
    public function testGetSectionsAssoc() {
        if (!is_array(AW_Collpur_Helper_Deals::getSectionsAssoc())) {
            $this->fail('getSectionsAssoc function is supposed to be an array of values');
        }
    }

    /**
     * @test
     */
    public function testGetSectionsKeys() {
        if (!is_array(AW_Collpur_Helper_Deals::getSectionsKeys())) {
            $this->fail('getSectionsAssoc function is supposed to be an array of values');
        }
    }

    /**
     * @test
     * Just make sure constants of class are present.
     * If not, shoud be added in tests     * 
     */
    public function testClassConstants() {
        AW_Collpur_Helper_Deals::CLOSED;
        AW_Collpur_Helper_Deals::FEATURED;
        AW_Collpur_Helper_Deals::NOT_RUNNING;
        AW_Collpur_Helper_Deals::RUNNING;
    }

}