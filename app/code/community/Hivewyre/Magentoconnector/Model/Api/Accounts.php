<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Model_Api_Accounts extends Hivewyre_Magentoconnector_Model_Api_Abstract
{

    /**
     * Make a request to get the segments.
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function getSegments(){

        $response = $this->_call('/external_api/v1/segments','GET');

        return $response; 
    }

    /**
     * Make a request to accounts method in order to save the a new account
     * @param  [type]     $email
     * @param  [type]     $website
     * @param  [type]     $segment
     * @param  [type]     $password
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function registerMerchant($email, $website, $segment, $password, $rap = null){
    	$data = array(
			'email'    => $email,
			'password' => $password,
    		'website' => array(
				'domain' 	=> $website,
				'segment'	=> $segment,
                'rap'       => Hivewyre_Magentoconnector_Model_Config::MAGENTO_RAP, //Magento Rap
    		)
    	);

    	$response = $this->_call('/external_api/v1/accounts', 'POST', $data);
    	return $response;
    }

    /**
     * Login Merchant
     * @param  [type]     $email
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function loginMerchant($email, $password){

        $data = array(
            'username' => $email,
            'password' => $password
        );

        $response = $this->_call('/external_api/v1/login', 'POST', $data);

        return $response;
    }

    /**
     * Get Merchant webisites
     * @param  [type]     $token
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function getMerchantWebsites($token){

        $response = $this->_call('/external_api/v1/advertiser/sites', 'GET', NULL, $token);

        return $response;
    }


    /**
     * Assign RAP to domain/site ID
     * @param  [type]     $domain_id
     * @param  [type]     $token
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
    public function connectMerchant($domain_id, $token, $rap){

        $data = array(
            'rap' => $rap,
        );

        $response = $this->_call('/external_api/v1/advertiser/sites/'.$domain_id, 'PATCH', $data, $token);

        return $response;
    } 
}