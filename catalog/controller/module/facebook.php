<?php  
class ControllerModuleFacebook extends Controller {
	
	protected function index($setting) {
		static $module = 0;
		
		$this->document->addScript('catalog/view/javascript/facebook/facebook_comment.js');
		
		$this->data['adminuid'] = $this->config->get('adminuid');
		
		$this->data['appid'] = $this->config->get('appid');
		
		$this->data['width'] = $setting['width'];
		
		$this->data['numpost'] = $setting['numpost'];
		
		
		$siteurl = "";
		
		if($setting['store'] != "default" && $setting['store']){
			$siteurl = $setting['store'];
		} else {
			$siteurl = $this->config->get('config_url');
		}
		
		if($setting['urltype']=="1"){
			$this->data['siteurl'] = $siteurl;
		} else if($setting['urltype']=="2") {
			$this->data['siteurl'] = $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		} else {
			$this->data['siteurl'] = $siteurl;
		}
		
		$this->data['colorscheme'] = $setting['colorscheme'];
			
		$this->data['module'] = $module++;
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/facebook.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/facebook.tpl';
		} else {
			$this->template = 'default/template/module/facebook.tpl';
		}
		
		$this->render();
	}
}
?>