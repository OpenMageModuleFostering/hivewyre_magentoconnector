<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Block_Conversion extends Hivewyre_Magentoconnector_Block_Abstract {

	private $_order_id;

	/**
	 * Get last order Id
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-01-24
	 */
	public function getOrderId(){
		if($this->_order_id == null) {
            $this->_order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        }
        return $this->_order_id;
	}

	/**
	 * Get order total amount (Excluding shipping costs)
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-01-24
	 */
	public function getOrderTotal(){
		$order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());		
		
		return $order->getSubtotal();
	}

	/**
	 * Get conversion script URL
	 * Used in the frontend
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-03-09
	 */
	public function getConversionScriptUrl(){
		$url = Hivewyre_Magentoconnector_Model_Config::HIVEWYRE_TRACKING_URL."/tagcontainer.js?id=".$this->getSiteId()."&type=0&order_id=".$this->getOrderId()."&value=".$this->getOrderTotal();

		return $url;	
	}	
}