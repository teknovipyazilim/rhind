<?php

class ControllerAhmesSetup extends Controller 
{
	
	private $error = array();
	private $data = array();

	public function index() {

		$this->load->language('ahmes/ahmes');
		//$this->load->model('ahmes/setup');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['txt_setup_table_formula'] = $this->language->get('txt_setup_table_formula');
		$this->data['txt_create'] = $this->language->get('txt_create');
		$this->data['txt_delete'] = $this->language->get('txt_delete');
		$this->data['url_create_formulas'] = $this->url->link('ahmes/setup/create_formulas', 'user_token=' . $this->session->data['user_token'], true);
		$this->data['url_drop_formulas'] = $this->url->link('ahmes/setup/drop_formulas', 'user_token=' . $this->session->data['user_token'], true);
		
		

		$this->showpage('setup');
	}
	public function showpage($template) {

		$this->document->setTitle($this->data['heading_title']);

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ahmes/'.$template, $this->data));
	}
	public function create_formulas() {

		$this->load->language('ahmes/ahmes');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->load->model('ahmes/setup');

		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "formulas` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `formula` TEXT NOT NULL , `date_added` DATETIME NOT NULL , `date_modified` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL , `status` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";
		$query = $this->db->query($sql);
		if($query)
		{
			$formularow = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'formula'");
			if(!$formularow->num_rows)
			{
				$this->db->query("ALTER  TABLE `" . DB_PREFIX . "product` ADD `formula` INT NOT NULL");
				
			}
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			foreach($languages as $language)
			{
				$formules[$language['code']] = 'W*H+5';
				
			}
			$query = $this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "formulas` (`id`, `name`, `formula`, `date_added`, `date_modified`, `status`) VALUES ('1', 'example', '".serialize($formules)."', NOW(), NOW(), '1')");
			echo "Ok";
		} else {
			echo "Err";
		}
	}
	public function drop_formulas() {
		
		$this->load->language('ahmes/ahmes');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$sql = "DROP TABLE  IF EXISTS `" . DB_PREFIX . "formulas`";
		$query = $this->db->query($sql);
		if($query)
		{
			echo 'Silindi';
		} else {
			echo 'Zaten Silindi';
			
		}
	}

}