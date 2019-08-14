<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Adminhtml_MagentoconnectorController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Returns a list of segments(categories) for website to register
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
	public function getSegmentsAction()
	{
        $segments = Mage::helper('magentoconnector')->getSegments();

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($segments));
	}

    /**
     * Get params from admin section and calll the method to register new accounts
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function registerMerchantAction(){

        $request = Mage::app()->getRequest();

        $result = Mage::helper('magentoconnector')->registerMerchant(
            $request->getParam('email'),
            $request->getParam('website'),
            $request->getParam('segment'),
            $request->getParam('password')
        );

        //Validate Success action
        if(!empty($result['success'])){
            Mage::getSingleton('core/session')->addSuccess('Thank you for registering. Please confirm your email.');
        }

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($result));

    }

    /**
     * Login user and get list of websites associated to the account.
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
    public function loginMerchantAction(){
        $request = Mage::app()->getRequest();

        $result = Mage::helper('magentoconnector')->loginMerchant(
            $request->getParam('email'),
            $request->getParam('password')
        );

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($result));
    }

    /**
     * Connect Merchant's account with Hivewyre
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
    public function connectMerchantAction(){
        $request = Mage::app()->getRequest();

        $result = Mage::helper('magentoconnector')->connectMerchant(
            $request->getParam('domain_id'),
            $request->getParam('token'),
            $request->getParam('rap')
        );

        //Validate Success action
        if(!empty($result['success'])){
            Mage::getSingleton('core/session')->addSuccess('Your account has been linked to Magento Successfully.');
        }

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($result));
    }
}