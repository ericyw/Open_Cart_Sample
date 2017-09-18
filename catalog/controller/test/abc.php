<?php

class ControllerTestAbc extends Controller {
	public function index() {
		echo 'Hello World';
		exit;	
	}
}
class ControllerModuleHelloWorld extends Controller {
	private $error = array(); // this is used to set the errors, if any
	
	public function index() {
		// loading the language file of helloworld 
		$this->language->load('module/helloworld'); 
		// set the title of the page to the heading title in the Language file i.e. Hello World
		$this->document->setTittle($this->language->get('heading_title'));
		// load the Setting Model  (All of the OpenCart Module & General Settings are saved using this Model )
		$this->load->model('setting/setting');
		
		// Start If: Validates and check if data is coming by save (POST) method
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Parse all the coming data to Setting Model to save it in database.
			$this->model_setting_setting->editSetting('helloworld', $this->request->post);
			// To display the success text on data save
			$this->session->data['success'] = $this->language->get('text_success');
			// Redirect to the Module Listing
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} // end if 
		
		// assign the language data for parsing it to view
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_disable'] = $this->language->get('text_disable');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		// this block returns the warning
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		// this block returns the error code 
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
  
		// making of breadcrumb to be displayed on site
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text' 		=> $this->language->get('text_module'),
			'href'		=> $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('heading_title'),
			'href'		=> $this->url-link('module/hellworld', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
	
		// this block checks, if the hello world text field is set, it parses it to view, otherwise get the default hello world text field from the database and parse it 
		if (isset($this->request->post['helloworld_module'])) {
			$this->data['modules'] = $this->request->post['helloworld_module'];
		} elseif ($this->config->get('helloworld_module')){
			$this->data['modules'] = $this->config->get('helloworld_module');			
		}
		
		// loading the design layout models
		$this->load_model('design/layout');
		// get all layouts available on system
		$this->data['layouts'] = $this->model_design_layout_getLayout();
		
		// loading the helloworld.tpl template
		$this->template = 'module/helloworld.tpl';
		// adding children to our default template i.e., helloword.tpl
		$this->children = array(
			'common/header',
			'common/footer'
		);
		// rendering the output
		$this->response->setOutput($this->render());
	}
	
	
	/* funcion that validates the data when Save Button is pressed */
		protected function validate() {
			/* Block to check the user permission to manipulate the module */	
			if (!$this->user->hasPermission('modify', 'module/helloworld')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			/* End Block */
			
			/* Block to check if the helloworld_text_field is properly set to save into database, otherwise the error is returned. */
			if (!$this->request->post['helloworld_text_field']) {
				$this->error['code'] = $this->language->get('error_code');
			}
			/* End Block */
			
			/* Block returns true if no error is found, else returns false if any error detected. */	
			if (!$this->error) {
				return true;
			} else {
				return false;
			}
			/* End block */
		
		}
	/* end validation function */
	
}
?>