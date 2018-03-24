<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class ControllerAhmesFormulas extends Controller {
	private $error = array();
	private $success = array();
	private $data = array();

	public function index() {
		$this->load->language('ahmes/ahmes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('ahmes/formulas');

		$this->getList();
	}
	protected function getList() 
	{
		$filter = array();
		$this->data['rows'] = $this->model_ahmes_formulas->getFormulas($filter);
		foreach($this->data['rows'] as $key => $row )
		{
			$this->data['rows'][$key]['edit'] =  $this->url->link('ahmes/formulas/edit', 'id='.$row['id'].'&user_token=' . $this->session->data['user_token'], true);
		}
		$this->data['add'] =  $this->url->link('ahmes/formulas/edit', '&user_token=' . $this->session->data['user_token'], true);
		$this->data['delete'] =  $this->url->link('ahmes/formulas/delete', '&user_token=' . $this->session->data['user_token'], true);
		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['txt_name'] = $this->language->get('txt_name');
		$this->data['txt_formula'] = $this->language->get('txt_formula');
		$this->data['txt_actions'] = $this->language->get('txt_actions');
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('ahmes/formulas', $this->data));

	}



	public function edit() 
	{
		$this->load->language('ahmes/ahmes');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('ahmes/formulas');
		if(isset($this->request->get['id']))
		{
			$id = $this->request->get['id'];	
		} else {
			$id = 0;
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) 
		{
			if(isset($this->request->post['id']))
			{
				$id = $this->request->post['id'];	
			} else {
				$id = 0;
			}
			$data =  isset($this->request->post['data'])?$this->request->post['data']:array();
			if($data)
			{
				if($id==0)
				{
					$id = $this->model_ahmes_formulas->addFormula($data);
					$this->data['success'] = 'Kayıt Eklendi';					
				} else {
					$this->model_ahmes_formulas->saveFormula($id, $data);
					$this->data['success'] = 'Kayıt tamam';					
				}
			} else {
				$this->data['error_warning'] = 'Kayıt olmadı';
				
			}
		} 
		
		if($id==0)
		{
			$rows = array('name'=>'','status'=>'1','formula'=>'','id'=>0);
		} else {
			$rows = $this->model_ahmes_formulas->getFormula($id);			
		}
		foreach($rows as $key => $row )
		{
			$this->data[$key] = $row;
		}
		
		$this->data['cancel'] = $this->url->link('ahmes/formulas', '&user_token=' . $this->session->data['user_token'], true);
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('ahmes/formula', $this->data));
		
		
	}

	public function delete() {
		$this->load->language('ahmes/formulas');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('ahmes/formulas');

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $formulas_id) {
				$this->model_ahmes_formulas->deleteFormula($formulas_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('ahmes/formulas', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getList();
	}


	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['formulas_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['formulasname'])) {
			$data['error_formulasname'] = $this->error['formulasname'];
		} else {
			$data['error_formulasname'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'formulas_token=' . $this->session->data['formulas_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('ahmes/formulas', 'formulas_token=' . $this->session->data['formulas_token'] . $url, true)
		);

		if (!isset($this->request->get['formulas_id'])) {
			$data['action'] = $this->url->link('ahmes/ahmes/add', 'formulas_token=' . $this->session->data['formulas_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('ahmes/ahmes/edit', 'formulas_token=' . $this->session->data['formulas_token'] . '&formulas_id=' . $this->request->get['formulas_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('ahmes/formulas', 'formulas_token=' . $this->session->data['formulas_token'] . $url, true);

		if (isset($this->request->get['formulas_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$formulas_info = $this->model_formulas_formulas->getformulas($this->request->get['formulas_id']);
		}

		if (isset($this->request->post['formulasname'])) {
			$data['formulasname'] = $this->request->post['formulasname'];
		} elseif (!empty($formulas_info)) {
			$data['formulasname'] = $formulas_info['formulasname'];
		} else {
			$data['formulasname'] = '';
		}

		if (isset($this->request->post['formulas_group_id'])) {
			$data['formulas_group_id'] = $this->request->post['formulas_group_id'];
		} elseif (!empty($formulas_info)) {
			$data['formulas_group_id'] = $formulas_info['formulas_group_id'];
		} else {
			$data['formulas_group_id'] = '';
		}

		$this->load->model('ahmes/formulas_group');

		$data['formulas_groups'] = $this->model_formulas_formulas_group->getformulasGroups();

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($formulas_info)) {
			$data['firstname'] = $formulas_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($formulas_info)) {
			$data['lastname'] = $formulas_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($formulas_info)) {
			$data['email'] = $formulas_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($formulas_info)) {
			$data['image'] = $formulas_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($formulas_info) && $formulas_info['image'] && is_file(DIR_IMAGE . $formulas_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($formulas_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($formulas_info)) {
			$data['status'] = $formulas_info['status'];
		} else {
			$data['status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ahmes/formulas_form', $data));
	}

	protected function validateForm() {
		if (!$this->formulas->hasPermission('modify', 'ahmes/formulas')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['formulasname']) < 3) || (utf8_strlen($this->request->post['formulasname']) > 20)) {
			$this->error['formulasname'] = $this->language->get('error_formulasname');
		}

		$formulas_info = $this->model_formulas_formulas->getformulasByformulasname($this->request->post['formulasname']);

		if (!isset($this->request->get['formulas_id'])) {
			if ($formulas_info) {
				$this->error['warning'] = $this->language->get('error_exists_formulasname');
			}
		} else {
			if ($formulas_info && ($this->request->get['formulas_id'] != $formulas_info['formulas_id'])) {
				$this->error['warning'] = $this->language->get('error_exists_formulasname');
			}
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		$formulas_info = $this->model_formulas_formulas->getformulasByEmail($this->request->post['email']);

		if (!isset($this->request->get['formulas_id'])) {
			if ($formulas_info) {
				$this->error['warning'] = $this->language->get('error_exists_email');
			}
		} else {
			if ($formulas_info && ($this->request->get['formulas_id'] != $formulas_info['formulas_id'])) {
				$this->error['warning'] = $this->language->get('error_exists_email');
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->get['formulas_id']))) {
			if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->formulas->hasPermission('modify', 'ahmes/formulas')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $formulas_id) {
			if ($this->formulas->getId() == $formulas_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}

		return !$this->error;
	}
}