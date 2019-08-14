<?php 
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Helper_Data extends Mage_Core_Helper_Abstract
{

    #Config paths
    const ENABLED_PATH         = 'hivewyre_settings/general/enabled';
    const SITE_ID_PATH         = 'hivewyre_settings/general/siteid';
    
    /**
     * Check if module is enabled and Site ID is set
     * @param  [type]     $store
     * @return boolean
     * @author edudeleon
     * @date   2016-01-24
     */
	public function isEnabled($store = null)
    {
        $clientId = $this->getSiteId($store);
        return $clientId && Mage::getStoreConfig(self::ENABLED_PATH, $store);
    }

    /**
     * Get Site ID
     * @param  [type]     $store
     * @return [type]
     * @author edudeleon
     * @date   2016-01-24
     */
	public function getSiteId($store = null)
    {
        return Mage::getStoreConfig(self::SITE_ID_PATH, $store);
    }

    /**
     * Call Method to get Segments and handle the result
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function getSegments(){
        $categories = array();
        try {
            $accountsApi = Mage::getModel('magentoconnector/api_accounts');
            $segments    = $accountsApi->getSegments();

            if(!empty($segments)){
                foreach ($segments as $value) {
                    $categories[$value['id']] = $value['name'];
                }
            }

        } catch (Exception $ex) {
            $error = array(
                    'success'   => FALSE,
                    'msg'       => Mage::helper('magentoconnector')->__($ex),
            );

        }
        
        return $categories;
    }


    /**
     * Call methdd that registers a new Merchant and handle result
     * @param  [type]     $email
     * @param  [type]     $website
     * @param  [type]     $segment
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function registerMerchant($email, $website, $segment, $password){

        try {
            $accountsApi = Mage::getModel('magentoconnector/api_accounts');
            $result = $accountsApi->registerMerchant($email, $website, $segment, $password);
     
            //Checking for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){
                return array(
                    'success'   => TRUE,
                    'site_id'   => $result['site_id']
                );
            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = Mage::helper('magentoconnector')->__('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Login user and get list of websites associated to the account.
     * @param  [type]     $email
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function loginMerchant($email, $password){
        try {
            $accountsApi = Mage::getModel('magentoconnector/api_accounts');
            $result      = $accountsApi->loginMerchant($email, $password);
        
            //Check for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){

                //Get website list
                $token   = $result["token"];
                $result2 = $accountsApi->getMerchantWebsites($token);

                //Checking errors second call
                $error_msg2 = $this->_getErrorMessages($result2);
                if(empty($error_msg2)){

                    $websites = array();
                    if(!empty($result2)){
                        $websites[0] = "Select a Website";
                    }
                    foreach ($result2 as $value) {
                        if($value["name_of_rap"] == "None" || $value["name_of_rap"] == NULL){
                            $websites[$value['id']] = $value['domain']  . " (Not Connected)";
                        } else {
                            $websites[$value['id']] = $value['domain'] . " (Connected to ".$value['name_of_rap'].")";
                        }
                    }

                    return array(
                        'success'   => TRUE,
                        'token'     => $token,
                        'sites'     => $websites
                    );
                } else {
                    return array(
                        'success' => FALSE,
                        'msg'     => $error_msg2
                    );
                }

            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = Mage::helper('magentoconnector')->__('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Connect Merchant 
     * @param  [type]     $domain_id
     * @param  [type]     $token
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
     public function connectMerchant($domain_id, $token, $rap){
        try {
            $accountsApi = Mage::getModel('magentoconnector/api_accounts');
            $result      = $accountsApi->connectMerchant($domain_id, $token, $rap);
            
            //Checking for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){
                return array(
                    'success'   => TRUE,
                    'site_id'   => $result['site_id']
                );
            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg,
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = Mage::helper('magentoconnector')->__('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Get error messages
     * @param  [type]     $result
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    private function _getErrorMessages($result){
       //Checking for Errors
        $error_msg = '';
        if(isset($result['errors'])){
            foreach ($result['errors'] as $key => $value) {
                $error_msg .= "[". strtoupper($key) ."] ";
                
                foreach ($value as $msg) {
                    $error_msg .= $msg;
                }

                $error_msg .= "</br>";
            }
        }

        return $error_msg;
    }
}