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

/**
 * aheadWorks Mobile Data Helper
 */
class AW_Mobile_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * iPhone Response
     */
    const IPHONE_RESPONSE = 'iPhone';

    /**
     * Android Response
     */
    const ANDROID_RESPONSE = 'Android';

    /**
     * BlackBerry Response
     */
    const BLACKBERRY_RESPONSE = 'BlackBerry';

    /**
     * iPhone Response
     */
    const WMOBILE_RESPONSE = 'IEMobile';

    /**
     * iPad Response
     */
    const IPAD_RESPONSE = 'iPad';
    
    /**
     * Frame Navigation Template path
     */
    const FRAME_NAVIGATION_TEMPLATE = 'catalog/navigation/top.phtml';

    /**
     * Path to Custom Design Config Path
     */
    const XML_PATH_MOBILE_CUSTOM_THEME = 'awmobile/design/custom_design';

    /**
     * Path to Custom Design Config Path
     */
    const XML_PATH_MOBILE_FOOTER_LINKS_BLOCK = 'awmobile/design/footer_links_block';

    /**
     * Path to Copyright Config Path
     */
    const XML_PATH_MOBILE_COPYRIGHT = 'awmobile/design/copyright';

    /**
     * Default package
     */
    const DEFAULT_PACKAGE = 'aw_mobile';

    /**
     * Default theme
     */
    const DEFAULT_THEME = 'iphone';

    /**
     * Target platform
     * @var string
     */
    protected $_target = null;

    /**
     * Is checkout messages prepared
     * @var bool
     */
    protected $_checkoutMessagesPrepared = false;

    /**
     * Retrives is iPhone Flag
     * @return boolean
     */
    public function iPhone()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPHONE_RESPONSE) !== false);
        }
        return false;
    }

    /**
     * Retrives is Android Flag
     * @return boolean
     */
    public function Android()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::ANDROID_RESPONSE) !== false);
        }
        return false;
    }


    /**
     * Retrives is Windows Mobile IE Flag
     * @return boolean
     */
    public function winMobile()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::WMOBILE_RESPONSE) !== false);
        }
        return false;
    }

    /**
     * Find version in the http_user_agent
     *
     * @param string $pattern
     * @param string $text
     * @return string
     */
    protected function _findVersion($pattern, $text)
    {
        $regExp = "/({$pattern} (?:(\d+)\.)?(?:(\d+)\.)?(\*|\d+))/";
        $toDelete = "{$pattern} ";

        $matches = array();
        preg_match($regExp, $text, $matches);
        if (count($matches)) {
            return str_replace($toDelete, "", $matches[0]);
        }
    }

    public function getAndroidVersion()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $this->_findVersion(strtolower(self::ANDROID_RESPONSE), strtolower($_SERVER['HTTP_USER_AGENT']));
        }
    }

    /**
     * Retrives is BlackBerry Flag
     * @return boolean
     */
    public function BlackBerry()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::BLACKBERRY_RESPONSE) !== false);
        } 
        return false;
    }

    /**
     * Retreive true if iPad device has been detected
     * @return bool
     */
    public function is_iPad()
    {
        if ($this->confIpadDetect()) {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPAD_RESPONSE) !== false);
            }
        }
        return false;
    }
    
    /**
     * Retrives customer session
     * @return Mage_Customer_Model_Session
     */
    protected function _customerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Retrives Show Desktop flag value
     * @return boolean
     */
    public function getShowDesktop()
    {
        return $this->_customerSession()->getShowDesktop();
    }

    /**
     * Set up State Changed mutex
     * @return AW_Mobile_Helper_Data
     */
    public function setStateChanged()
    {
        $this->_customerSession()->setStateChanged(true);
    }

    /**
     * Retrivces State Changed mutex
     * @return boolean
     */
    public function isStateChanged()
    {
        $state = $this->_customerSession()->getStateChanged();
        $this->_customerSession()->setStateChanged(false);
        return $state;
    }

    /**
     * Retrives Page Cache enabled flag
     * @return boolean
     */
    public function isPageCache()
    {
        return Mage::app()->useCache('full_page');
    }

    /**
     * Set up Forced flag
     * @return AW_Mobile_Helper_Data
     */
    public function setForced()
    {
        $this->_customerSession()->setForced(true);
        return $this;
    }

    /**
     * Retrivces Forced flag
     * @return boolean
     */
    public function isForced()
    {
        return $this->_customerSession()->getForced();
    }

    /**
     * Set up Show Desktop flag
     * @param boolean $value
     */
    public function setShowDesktop($value)
    {
        $this->setStateChanged();
        $this->setForced();
        $this->_customerSession()->setShowDesktop($value);
    }

    /**
     * Retrive config value of Mobile Detect option
     * @return boolen
     */
    public function confMobileDetect()
    {
        return !!Mage::getStoreConfig('awmobile/behaviour/mobile_detect');
    }

    /**
     * Retrive config value of iPad Detect option
     * @return boolen
     */
    public function confIpadDetect()
    {
        return !!Mage::getStoreConfig('awmobile/behaviour/ipad_detect');
    }

    /**
     * Compare param $version with magento version
     * @param string $version
     * @return boolean
     */
    public function checkVersion($version)
    {
        return version_compare(Mage::getVersion(), $version, '>=');
    }

    /**
     * Retrive is Magento Enterprise Edition Flag
     * @return boolean
     */
    public function isEE()
    {
        return $this->checkVersion('1.7.0.0');
    }

    /**
     * Retrives flag that old POST format required for chechout street address
     * @return boolean
     */
    public function isOldStreetFormat()
    {
        if (!$this->isEE()) {
            return !$this->checkVersion('1.4.2.0');
        } else {
            return !$this->checkVersion('1.9.0.0');
        }
    }


    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        if (method_exists(Mage::helper('core'), 'stripTags')) {
            return parent::stripTags($data, $allowableTags = null, $escape = false);
        }
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    public function categorizr(){

    $catergorize_tablets_as_desktops = TRUE;  //If TRUE, tablets will be categorized as desktops
    $catergorize_tvs_as_desktops     = TRUE;  //If TRUE, smartTVs will be categorized as desktops

    if (!array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
        return "desktop";
    }
    $ua = $_SERVER['HTTP_USER_AGENT'];
    // Check if session has already started, otherwise E_NOTICE is thrown

            // Check if user agent is a smart TV - http://goo.gl/FocDk
            if ((preg_match('/GoogleTV|SmartTV|Internet.TV|NetCast|NETTV|AppleTV|boxee|Kylo|Roku|DLNADOC|CE\-HTML/i', $ua)))
            {
                    $device = "tv";
            }
            // Check if user agent is a TV Based Gaming Console
            else if ((preg_match('/Xbox|PLAYSTATION.3|Wii/i', $ua)))
            {
                    $device = "tv";
            }  
            // Check if user agent is a Tablet
            else if((preg_match('/iP(a|ro)d/i', $ua)) || (preg_match('/tablet/i', $ua)) && (!preg_match('/RX-34/i', $ua)) || (preg_match('/FOLIO/i', $ua)))
            {
                    $device = "tablet";
            }
            // Check if user agent is an Android Tablet
            else if ((preg_match('/Linux/i', $ua)) && (preg_match('/Android/i', $ua)) && (!preg_match('/Fennec|mobi|HTC.Magic|HTCX06HT|Nexus.One|SC-02B|fone.945/i', $ua)))
            {
                    $device = "tablet";
            }
            // Check if user agent is a Kindle or Kindle Fire
            else if ((preg_match('/Kindle/i', $ua)) || (preg_match('/Mac.OS/i', $ua)) && (preg_match('/Silk/i', $ua)))
            {
                    $device = "tablet";
            }
            // Check if user agent is a pre Android 3.0 Tablet
            else if ((preg_match('/GT-P10|SC-01C|SHW-M180S|SGH-T849|SCH-I800|SHW-M180L|SPH-P100|SGH-I987|zt180|HTC(.Flyer|\_Flyer)|Sprint.ATP51|ViewPad7|pandigital(sprnova|nova)|Ideos.S7|Dell.Streak.7|Advent.Vega|A101IT|A70BHT|MID7015|Next2|nook/i', $ua)) || (preg_match('/MB511/i', $ua)) && (preg_match('/RUTEM/i', $ua)))
            {
                    $device = "tablet";
            } 
            // Check if user agent is unique Mobile User Agent	
            else if ((preg_match('/BOLT|Fennec|Iris|Maemo|Minimo|Mobi|mowser|NetFront|Novarra|Prism|RX-34|Skyfire|Tear|XV6875|XV6975|Google.Wireless.Transcoder/i', $ua)))
            {
                    $device = "mobile";
            }
            // Check if user agent is an odd Opera User Agent - http://goo.gl/nK90K
            else if ((preg_match('/Opera/i', $ua)) && (preg_match('/Windows.NT.5/i', $ua)) && (preg_match('/HTC|Xda|Mini|Vario|SAMSUNG\-GT\-i8000|SAMSUNG\-SGH\-i9/i', $ua)))
            {
                    $device = "mobile";
            }
            // Check if user agent is Windows Desktop
            else if ((preg_match('/Windows.(NT|XP|ME|9)/', $ua)) && (!preg_match('/Phone/i', $ua)) || (preg_match('/Win(9|.9|NT)/i', $ua)))
            {
                    $device = "desktop";
            }  
            // Check if agent is Mac Desktop
            else if ((preg_match('/Macintosh|PowerPC/i', $ua)) && (!preg_match('/Silk/i', $ua)))
            {
                    $device = "desktop";
            } 
            // Check if user agent is a Linux Desktop
            else if ((preg_match('/Linux/i', $ua)) && (preg_match('/X11/i', $ua)))
            {
                    $device = "desktop";
            } 
            // Check if user agent is a Solaris, SunOS, BSD Desktop
            else if ((preg_match('/Solaris|SunOS|BSD/i', $ua)))
            {
                    $device = "desktop";
            }
            // Check if user agent is a Desktop BOT/Crawler/Spider
            else if ((preg_match('/Bot|Crawler|Spider|Yahoo|ia_archiver|Covario-IDS|findlinks|DataparkSearch|larbin|Mediapartners-Google|NG-Search|Snappy|Teoma|Jeeves|TinEye/i', $ua)) && (!preg_match('/Mobile/i', $ua)))
            {
                    $device = "desktop";
            }  
            // Otherwise assume it is a Mobile Device
            else {
                    $device = "mobile";
            }

        // Categorize Tablets as desktops
        if ($catergorize_tablets_as_desktops && $device == "tablet"){
                $device = "desktop";
        }

        // Categorize TVs as desktops
        if ($catergorize_tvs_as_desktops && $device == "tv"){
                $device = "desktop";
        }
       
        return $device;

    }

    // Returns true if desktop user agent is detected
    public function isDesktop(){
            $device = $this->categorizr();
            if($device == "desktop"){
                    return TRUE;
            }
            return FALSE;
    }
    // Returns true if tablet user agent is detected
    public function isTablet(){
            $device = $this->categorizr();
            if($device == "tablet"){
                    return TRUE;
            }
            return FALSE;
    }
    // Returns true if tablet user agent is detected
    public function isTV(){
            $device = $this->categorizr();
            if($device == "tv"){
                    return TRUE;
            }
            return FALSE;
    }
    // Returns true if mobile user agent is detected
    public function isMobile(){
            $device = $this->categorizr();
            if($device == "mobile"){
                    return TRUE;
            }
            return FALSE;
    }
    
    /**
     * Retrives target platform
     * @return string
     */
    public function getTargetPlatform()
    {
        if ($this->getDisabledOutput()) {
            return AW_Mobile_Model_Observer::TARGET_DESKTOP;
        }
        if (!$this->_target) {
            $target = AW_Mobile_Model_Observer::TARGET_DESKTOP;
            if ($this->isForced()) {
                $target = $this->getShowDesktop() ? AW_Mobile_Model_Observer::TARGET_DESKTOP : AW_Mobile_Model_Observer::TARGET_MOBILE;
            } elseif ($this->confMobileDetect() && (
                /* Select a platform */
                $this->isMobile() || $this->is_iPad()
            )
            ) { 
                $target = AW_Mobile_Model_Observer::TARGET_MOBILE;
            }
            $this->_target = $target;
        }
        return $this->_target;
    }

    /**
     * Retrives disabled output of extension flag
     * @return boolean
     */
    public function getDisabledOutput()
    {
        return !!Mage::getStoreConfig('advanced/modules_disable_output/AW_Mobile');
    }

    /**
     * Retrives additional navigation tools
     *
     * @return boolean
     */
    public function wantNavigationTools()
    {
        if ($this->Android() && version_compare($this->getAndroidVersion(), '1.6', '<=')) {
            return true;
        }
        return (!$this->iPhone() && !$this->Android());
    }

    public function isOldAndroid()
    {
        return ($this->Android() && version_compare($this->getAndroidVersion(), '1.6', '<='));
    }

    protected function _getCustomDesign($key)
    {
        $package = null;
        $theme = null;
        if ($customTheme = Mage::getStoreConfig(self::XML_PATH_MOBILE_CUSTOM_THEME)) {
            list($package, $theme) = explode('/', $customTheme);
            return $$key;
        }
        return null;
    }

    /**
     * Retrives default package
     * @return string
     */
    public function getMobilePackage()
    {
        if ($this->_getCustomDesign('package')) {
            return $this->_getCustomDesign('package');
        }
        return self::DEFAULT_PACKAGE;
    }

    /**
     * Retrives default theme
     * @return string
     */
    public function getMobileTheme()
    {
        if ($this->_getCustomDesign('theme')) {
            return $this->_getCustomDesign('theme');
        }
        return self::DEFAULT_THEME;
    }

    public function getCustomFooterLinksHtml()
    {
        if ($blockId = Mage::getStoreConfig(self::XML_PATH_MOBILE_FOOTER_LINKS_BLOCK)) {
            $block = Mage::app()->getLayout()->createBlock('cms/block');
            if ($block) {
                return $block->setBlockId($blockId)->toHtml();
            }
        }
        return null;
    }

    public function getCopyright()
    {
        return Mage::getStoreConfig(self::XML_PATH_MOBILE_COPYRIGHT);
    }

    public function prepareCartMessages($messagesBlock, $multishipping = false)
    {
        $cart = Mage::getSingleton('checkout/cart');
        if ($cart->getQuote()->getItemsCount()) {
            if (!$cart->getQuote()->validateMinimumAmount($multishipping)) {
                $warning = Mage::getStoreConfig('sales/minimum_order/description');
                $collection = $messagesBlock->getMessageCollection();
                $messages = array();
                foreach($collection->getItems() as $message) {
                    if ($message->getCode() !== $warning) {
                        $messages[] = $message;
                    }
                }
                $collection->clear();
                foreach ($messages as $message) {
                    $collection->add($message);
                }
                $collection->add(Mage::getSingleton('core/message')->notice($warning));
                $messagesBlock->setMessages($collection);
            }
        }
    }
}
