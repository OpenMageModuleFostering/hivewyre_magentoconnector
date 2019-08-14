<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Block_Tracking extends Hivewyre_Magentoconnector_Block_Abstract {

	/**
	 * Get tracking script URL
	 * Used in the frontend
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-03-09
	 */
	public function getTrackingScriptUrl(){
		$url = Hivewyre_Magentoconnector_Model_Config::HIVEWYRE_TRACKING_URL."/tagcontainer.js?id=".$this->getSiteId()."&type=1";

		return $url;	
	}
}