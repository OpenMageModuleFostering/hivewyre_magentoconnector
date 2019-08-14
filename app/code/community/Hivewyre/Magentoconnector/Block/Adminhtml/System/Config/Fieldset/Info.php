<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

class Hivewyre_Magentoconnector_Block_Adminhtml_System_Config_Fieldset_Info 
	extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

		protected $_template = 'hivewyre_magentoconnector/system/config/fieldset/info.phtml';

		public function render(Varien_Data_Form_Element_Abstract $element) {
        	return $this->toHtml();
    	}  	
}