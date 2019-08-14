<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Model_Api_Abstract extends Mage_Core_Model_Abstract
{

    /**
     * Returns the method URL
     * @param  [type]     $path
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    protected function _getUrl($path)
    {
        return Hivewyre_Magentoconnector_Model_Config::ENDPOINT_URL.$path;
    }

    /**
     * Return the Authorization String encoded
     * @param  [type]     $method
     * @param  [type]     $endpoint
     * @param  [type]     $data
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    private function _getAuthorizationString($method, $endpoint, $data){
        //Get this from config
        $this->public_key = Hivewyre_Magentoconnector_Model_Config::HIVEWYRE_PUBLIC_KEY;
        $this->secret_key = Hivewyre_Magentoconnector_Model_Config::HIVEWYRE_SECRET_KEY;

        if($data){
            $request_body = utf8_encode(json_encode($data));
        } else {
            $request_body = '';
        }
        
        $data_request    = $method . "\n" . $endpoint . "\n" . $request_body;
        $encoded_request = 'Hivewyre '.$this->public_key.':'.base64_encode(hash_hmac("sha1", $data_request, $this->secret_key, $raw_output=TRUE));
        
        return $encoded_request;
    }

    /**
     * Makes the API Call
     * @param  [type]     $endpoint
     * @param  string     $method
     * @param  [type]     $data
     * @param  boolean    $token
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    protected function _call($endpoint, $method = 'POST', $data = null, $token = null)
    {
        //Prepare URL
        $url = $this->_getUrl($endpoint);
       
        $method = strtoupper($method);

        //Getting Authorization String
        $auth_string = $this->_getAuthorizationString($method, $endpoint, $data);
        
        //Instantiate the
        $client = new Zend_Http_Client($url);
        $client->setMethod($method);

        //Preparing headers
        $headers = array(
            'Authorization' => $auth_string,
            'Content-Type'  => 'application/json'
        );

        if($token){
            $headers['Advertiser-Authorization'] = "Token ".$token;
        }

        $client->setHeaders($headers);

        if($method == 'POST' || $method == "PUT" || $method == "PATCH") {
            $client->setRawData(json_encode($data), 'application/json');
        }

        Mage::log(
            print_r(
                array(
                   'url'     => $url,
                   'method'  => $method,
                   'headers' => $headers,
                   'data'    => json_encode($data),
                ),
                true
            ),
            null,
            'hivewyre.log'
        );

        try {
            $response = $client->request();

        } catch ( Zend_Http_Client_Exception $ex ) {
            Mage::log('Call to ' . $url . ' resulted in: ' . $ex->getMessage(), Zend_Log::ERR, 'hivewyre.log');
            Mage::log('--Last Request: ' . $client->getLastRequest(), Zend_Log::ERR, 'hivewyre.log');
            Mage::log('--Last Response: ' . $client->getLastResponse(), Zend_Log::ERR, 'hivewyre.log');

            return $ex->getMessage();
        }

        //Prepare response
        $body = json_decode($response->getBody(), true);

        //Log response
        Mage::log(var_export($body, true), Zend_Log::DEBUG, 'hivewyre.log');
        
        return $body;
    }
}