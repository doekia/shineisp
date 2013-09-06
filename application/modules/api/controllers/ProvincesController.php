<?php

/**
 * ProductscategoriesController
 * Manage the product category table
 * @version 1.0
 */

class Api_ProvincesController extends Shineisp_Api_Controller_Action {
	
	protected $productscategories;  
    
    public function preDispatch() {
        $registry = Shineisp_Registry::getInstance ();
        $this->translations = $registry->Zend_Translate;
        
        $application = new Zend_Application( APPLICATION_ENV,  APPLICATION_PATH . '/configs/application.ini' );
        $this->config = $application->bootstrap()->getOptions();        
    }
    
    public function soapAction(  ) {
        self::soap( 'Shineisp_Api_Provinces' );
        exit();
    }
    
    public function wsdlAction(  ) {
        self::wsdl( 'Shineisp_Api_Provinces' );
        exit();
    }
        
}