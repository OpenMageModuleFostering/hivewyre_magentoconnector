<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Model_Config extends Mage_Core_Model_Abstract
{
   /*
    * API Endpoint
    */
    const ENDPOINT_URL        = 'https://clients.hivewyre.com';
    
    /*
    * Registered Account Partner
    */
    const MAGENTO_RAP         = '1dfc3238c2294c688073f56c64559cb4';
    
    /**
    * Hivewyre Public API Key
    */
    const HIVEWYRE_PUBLIC_KEY = "ddb070fe0cf9401fbc4b256e1039fbc0";
    
    /**
    * Hivewyre Secret API Key
    */
    const HIVEWYRE_SECRET_KEY = "d8820147c6c1495081017f14f1c2104f";
    
    /**
     * Hivewyre tracking URL / Used in frontend header
     */
    const HIVEWYRE_TRACKING_URL  = "https://js.b1js.com";
}