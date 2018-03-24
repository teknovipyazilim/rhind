<?php
class ControllerAhmesAjax extends Controller {
	/*
	$json['state'] = 0. Unknow, -1. Fail, 1. Success, 
	*/
	public $json = array('state'=>'0', 'action'=>'', 'message'=>'Unknow Result', 'list'=>array(), 'html'=>'' );
	public function index() 
	{
		$this->load->language('tool/upload');
		$json = array();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function calculate() 
	{
		$product_id=isset($_POST['product_id'])?$_POST['product_id']:0;
		$quantity=isset($_POST['quantity'])?$_POST['quantity']:0;
		$option=isset($_POST['option'])?$_POST['option']:array();
		$cart = array('cart_id'=>0, 'product_id'=>$product_id, 'quantity'=>$quantity,  'option'=>json_encode($option), 'recurring_id'=>0);

		$result = $this->cart->getProducts($cart);
		//die();
		$product = current($result);
		if($product)
		{
			$product['tax'] = 'Ex Tax: '.$this->currency->format($product['total'], $this->session->data['currency']);
			if ($this->config->get('config_tax')) {

				$product['total'] = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {

				$product['total'] =$product['total'];
			}
			$product['skin'] = array('thumb'=>'', 'popup'=>'');
			$this->load->model('tool/image');
			foreach($product['option'] as $option)
			{
				
				/*if(file_exists($skin_file))
				{


					$product['skin']['thumb'] = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
					$product['skin']['popup'] = $this->model_tool_image->resize($skin, $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
				}*/
			}

			$this->json['state'] 		= '1';
			$this->json['action'] 	= 'calculate';
			$this->json['message'] 	= 'Ürün Hesaplama Başarılı';
			$this->json['list'] 		= $product;
			$this->json['html'] 		= '';
			
		} else {
			$this->json['list'] 		= array('total'=>0, 'tax'=>'0');;
			
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->json));
	}
	
	
}
