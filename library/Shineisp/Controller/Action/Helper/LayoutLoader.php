<?php
/**
 * Shineisp_Controller_Action_Helper_LayoutLoader
 * Handle the layout design configuration
 * @author Shine Software Staff
 *
 */
class Shineisp_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract {
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
	 */
	public function preDispatch() {
		$module = $this->getRequest ()->getModuleName ();
		$controller = $this->getRequest ()->getControllerName ();
		
		$keywords = Settings::findbyParam ( 'default_html_keywords' );
		$description = Settings::findbyParam ( 'default_html_description' );
		$copyright = Settings::findbyParam ( 'default_copyright' );
		
		// Get the skin paramenter set in the Settings Table in the database		
		if($module == "default"){
			$customskin = Settings::findbyParam ( 'skin' );
			$skin = !empty ( $customskin ) && (file_exists(PUBLIC_PATH . "/skins/$module/$customskin")) ? $customskin : "blank";
		}elseif($module == "admin"){
			$customskin = Settings::findbyParam ( 'adminskin' );
			$skin = !empty ( $customskin ) && (file_exists(PUBLIC_PATH . "/skins/$module/$customskin")) ? $customskin : "blank";        
		}else{
			$customskin = "";
			$skin = "";
		}
		
		// Get all the resources set in the layout.xml file
		$css = Shineisp_Commons_Layout::getResources ( $module, $controller, "css", $skin );
		$js = Shineisp_Commons_Layout::getResources ( $module, $controller, "js", $skin );
		$template = Shineisp_Commons_Layout::getTemplate ( $module, $controller, $skin );
		
		// Setting up the HEAD Section
		$view = new Zend_View ();
		
		$view->doctype ( 'XHTML1_TRANSITIONAL' );
		#$view->headMeta ()->appendHttpEquiv ( 'Content-Type', 'text/html;charset=utf-8' );
		
		$view->headTitle ( Settings::findbyParam ( 'default_html_title' ) );
		$view->headMeta ()->setName ( 'robots', "INDEX, FOLLOW");
		$view->headMeta ()->setName ( 'author', !empty($copyright) ? $copyright : "Shine Software Company" );
		$view->headMeta ()->setName ( 'keywords', !empty($keywords) ? $keywords : "shine software, isp software" );
		$view->headMeta ()->setName ( 'description', !empty($description) ? $description : "This is a Shine Software application" );
		$view->headTitle ()->setSeparator(' / ');

		// Custom XML file inclusion of the js files 
		if (! empty ( $js )) {
			// Fast including of the js file using the module name
			if(file_exists(PUBLIC_PATH . "/skins/$module/$skin/js/$module.js")){
				$js[] = array('resource' => "/skins/$module/$skin/js/$module.js", 'position' => 'admin_endbody');
			}
				
			// Fast including of the js file for the controller
			if(file_exists(PUBLIC_PATH . "/skins/$module/$skin/js/$controller.js")){
				$js[] = array('resource' => "/skins/$module/$skin/js/$controller.js", "position" => 'admin_endbody');
			}
			
			// Check the caches administrator preferences
			if(Settings::findbyParam('jscompression')){
				
				@mkdir(PUBLIC_PATH . "/cache/");
				
				// Create the JS Compressor object
				$compressor = new Shineisp_Commons_jsCompressor();
				
				foreach ($js as $item){
					$compressor->add(PUBLIC_PATH . $item['resource']);
				}

				// Compress and minify the javascript files
				$filecompressed = $compressor->simpleMode()->hideDebugInfo()
										   	 ->cacheDir(PUBLIC_PATH . "/cache/")
										     ->write();

				$deferringjs = "<script>(function() { var s = document.createElement(\"script\"); s.type = \"text/javascript\"; s.async = true; s.src = \"/cache/$filecompressed\"; var x = document.getElementsByTagName(\"script\")[0]; x.parentNode.insertBefore(s, x) })();</script>";
				
				// Add the compressed file in the deferring mode
				if($module == "admin"){
					$view->placeholder ("admin_htmlhead" )->append ($deferringjs);
				}elseif($module == "default"){
					$view->placeholder ("htmlhead" )->append ($deferringjs);
				}
				
			}else{
				
				foreach ($js as $item){

					// check if the css or the js is a conditional item
					$conditional = !empty($item['conditional']) ? array('conditional' => $item['conditional']) : null;

					// check the position of the item in the page
					if(!empty($item['position']) ){
						
						$view->InlineScript ()->setFile ($item['resource'], 'text/javascript', $conditional)->toString();
						$view->placeholder ( $item['position'] )->append ($view->InlineScript ()->toString());
					}else{
						$view->headScript ()->setFile ($item['resource'], 'text/javascript', $conditional);
						$view->placeholder ( "admin_endbody" )->append ($view->InlineScript ()->toString());
					}
				}
			}
		}
		
		// Custom XML file inclusion of the css files
		if (! empty ( $css )) {

			// Fast including of the css file using the module name
			if(file_exists(PUBLIC_PATH . "/skins/$module/$skin/css/$module.css")){
				$css[]['resource'] = "/skins/$module/$skin/css/$module.css";
			}

			// Fast including of the css file for the controller
			if(file_exists(PUBLIC_PATH . "/skins/$module/$skin/css/$controller.css")){
				$css[]['resource'] = "/skins/$module/$skin/css/$controller.css";
			}
			
			// Check the caches administrator preferences
			if(Settings::findbyParam('csscompression')){
				
				@mkdir(PUBLIC_PATH . "/cache/");
				
				// Create the JS Compressor object
				$compressor = new Shineisp_Commons_cssCompressor();
				
				foreach ($css as $item){
					$compressor->add(PUBLIC_PATH . $item['resource']);
				}
				
				// Compress and minify the stylesheet files
				$filecompressed = $compressor->cacheDir(PUBLIC_PATH . "/cache/")->write();
				
				$view->headLink ()->appendStylesheet ( "/cache/$filecompressed"  );
				
			}else{
				
				foreach ( $css as $item ) {
					$conditional = !empty($item['conditional']) ? $item['conditional'] : null;
					$view->headLink ()->appendStylesheet ( $item['resource'], 'screen', $conditional );
				}
			}
			
		}
		
		$customcss = Settings::findbyParam ( 'css' );
		if($customcss){
		    $view->placeholder ( "htmlhead" )->append ("<style>" . trim(preg_replace('/\s\s+/', ' ', $customcss)) . "</style>");
		}
		
		// Enable and Add the RSS Shine Software reference
		$view->headLink ()->appendAlternate('/rss/', 'application/rss+xml', 'RSS Feed');
		$view->headLink ()->headLink(array('rel' => 'icon', 'type' => 'image/x-icon', 'href' => "/skins/$module/$skin/images/favicon.ico"));
				
		$view->placeholder ( "endbody" )->append ( Settings::findbyParam ( 'default_html_code' ) );
		$view->placeholder ( "htmlhead" )->append ( Settings::findbyParam ( 'default_html_head' ) );
		$view->placeholder ( "endbody" )->append ( Settings::findbyParam ( 'google_analytics_code' ) );

		// Change the default path script
		if (file_exists ( APPLICATION_PATH . "/modules/$module/views/$skin/" )) {
			$this->getActionController ()->view->addBasePath ( APPLICATION_PATH . "/modules/$module/views/$skin/" );
		}else{
			$this->getActionController ()->view->addBasePath ( APPLICATION_PATH . "/modules/$module/views/blank/" );
		}
        
		// Set the additional path for the layout templates
		if( $this->getActionController ()->getHelper ( 'viewRenderer' )->getNoRender() == false ) {
    		if (file_exists ( PUBLIC_PATH . "/skins/$module/$skin/" )) {
    			
    			$this->getActionController ()->view->addBasePath ( PUBLIC_PATH . "/skins/$module/$skin/" );
    			
    			// Setting the Template to be used
    			$this->getActionController ()->getHelper ( 'layout' )->setLayout ( $template );
    		
    		} else {
                
    			if ($module != "system") { // System module doesn't need the template folder
    				throw new Exception("Template folder has not been found in: " . PUBLIC_PATH . "/skins/$module/$skin/" );
    			}
    		}
        }
	}
}