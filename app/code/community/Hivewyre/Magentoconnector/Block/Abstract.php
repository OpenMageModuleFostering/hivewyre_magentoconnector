<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Block_Abstract extends Mage_Core_Block_Template {

	/**
	 * Verify if module is enabled
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-01-24
	 */
	public function _toHtml()
    {
        if (Mage::helper('magentoconnector')->isEnabled()){
            return parent::_toHtml();
        }
    }

    /**
     * Return Site Id
     * @return [type]
     * @author edudeleon
     * @date   2016-01-24
     */
    public function getSiteId(){
    	return Mage::helper('magentoconnector')->getSiteId();
    }

}