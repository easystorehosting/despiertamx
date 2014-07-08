<?php
class ControllerModuleFblogin extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/fblogin');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

		$opencartversion = (int)VERSION.'.'.str_replace('.',"",substr(VERSION,2));

		if((float)$opencartversion<1.51){
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {			
				$module=array();
				$i=0;
				if(isset($this->request->post['fblogin_module'])){
					foreach($this->request->post['fblogin_module'] as $k=>$v){
						foreach($v as $key=>$value){
							$this->request->post['fblogin_'.$k.'_'.$key]=$value;
						}
						$module[]=$i;
						$i++;
					}
				}
				$this->request->post['fblogin_module']=implode(',',$module);
			}
		}
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fblogin', $this->request->post);					
			$this->session->data['success'] = $this->language->get('text_success');						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		$this->data['languages'] = $languages;
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_creator'] = $this->language->get('entry_creator');
		$this->data['entry_version'] = $this->language->get('entry_version');
		$this->data['entry_updated'] = $this->language->get('entry_updated');
		$this->data['entry_licence'] = $this->language->get('entry_licence');

		$this->data['entry_apikey'] = $this->language->get('entry_apikey');
		$this->data['entry_apisecret'] = $this->language->get('entry_apisecret');
		$this->data['entry_pwdsecret'] = $this->language->get('entry_pwdsecret');
		$this->data['entry_button'] = $this->language->get('entry_button');
		$this->data['entry_button_desc'] = $this->language->get('entry_button_desc');
                $this->data['entry_fbfriend_status'] = $this->language->get('entry_fbfriend_status');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/fblogin', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/fblogin', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();

		foreach ($languages as $language) {
			if (isset($this->request->post['fblogin_button_' . $language['language_id']])) {
				$this->data['fblogin_button_' . $language['language_id']] = $this->request->post['fblogin_button_' . $language['language_id']];
			} else {
				$this->data['fblogin_button_' . $language['language_id']] = $this->config->get('fblogin_button_' . $language['language_id']);
			}
		}

		if (isset($this->request->post['fblogin_apikey'])) {
			$this->data['fblogin_apikey'] = $this->request->post['fblogin_apikey'];
		} elseif ($this->config->get('fblogin_apikey')) { 
			$this->data['fblogin_apikey'] = $this->config->get('fblogin_apikey');
		} else $this->data['fblogin_apikey'] = '';

		if (isset($this->request->post['fblogin_apisecret'])) {
			$this->data['fblogin_apisecret'] = $this->request->post['fblogin_apisecret'];
		} elseif ($this->config->get('fblogin_apisecret')) { 
			$this->data['fblogin_apisecret'] = $this->config->get('fblogin_apisecret');
		} else $this->data['fblogin_apisecret'] = '';

		if (isset($this->request->post['fblogin_pwdsecret'])) {
			$this->data['fblogin_pwdsecret'] = $this->request->post['fblogin_pwdsecret'];
		} elseif ($this->config->get('fblogin_pwdsecret')) { 
			$this->data['fblogin_pwdsecret'] = $this->config->get('fblogin_pwdsecret');
		} else $this->data['fblogin_pwdsecret'] = '';

		if (isset($this->request->post['fbfriend_enabled'])) {
			$this->data['fbfriend_enabled'] = $this->request->post['fbfriend_enabled'];
		} else {
			$this->data['fbfriend_enabled'] = $this->config->get('fbfriend_enabled');
		}

		if($opencartversion<1.51){
			$this->data['modules']=array();
			$toarray=$obj_get='';
			if(isset($this->request->post['fblogin_module'])){
				$toarray=$this->request->post['fblogin_module'];
				$obj_get='post';
			}
 			elseif ($this->config->get('fblogin_module')!='') { 
				$toarray=$this->config->get('fblogin_module');
				$obj_get='config';
			}
			if($toarray!=',' && $obj_get!=''){
				$i=count(explode(',',$toarray));
				$array_key=array('layout_id','position','status','sort_order');
				for($k=0; $k<$i; $k++){
					$array=array();
					foreach($array_key as $key){
						if($obj_get=="config")
							$array[$key]=$this->config->get('fblogin_'.$k.'_'.$key);
						else
							$array[$key]=$this->request->post['fblogin_'.$k.'_'.$key];
					}
					$this->data['modules'][] = $array;
				}
			}
		}
		else{		
			if (isset($this->request->post['fblogin_module'])) {
				$this->data['modules'] = $this->request->post['fblogin_module'];
			} elseif ($this->config->get('fblogin_module')) { 
				$this->data['modules'] = $this->config->get('fblogin_module');
			}		
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/fblogin.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/fblogin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['fblogin_apikey'] || !$this->request->post['fblogin_apisecret'] || !$this->request->post['fblogin_pwdsecret']) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>