<?php
class ModelAhmesFormulas extends Model {
	public function getFormula($id) 
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "formulas` WHERE `id`=".(int)$id;
		$query = $this->db->query($sql);
		if($query->num_rows)
		{
			$query->row['formula'] = unserialize($query->row['formula']);
			return $query->row;
		} else {
			return array();
		}
	}

	public function getFormulas($data = array()) 
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "formulas` ";
		$query = $this->db->query($sql);
		if($query->num_rows)
		{
			foreach($query->rows as $key=>$row)
			{
				
				$query->rows[$key]['formula'] = unserialize($row['formula']);
			}
			return $query->rows;
		} else {
			return array();
		}	
	}
	
	public function addFormula($data) 
	{
		$data['formula'] = serialize($data['formula']);
		print_r($data);
		foreach($data as $key => $d)
		{
			$data[$key] = $this->db->escape($d);
		}
		extract($data);	
		$sql = "INSERT INTO `" . DB_PREFIX . "formulas` (`id`, `name`, `formula`, `date_added`, `date_modified`, `status`) VALUES (NULL, '$name', '".$formula."', NOW(), NOW(), '$status')";
		$query = $this->db->query($sql);
		if($query)
		{
			return $this->db->getLastId();
		} else {
			return 0;
		}
	}

	public function saveFormula($id, $data) 
	{
		$data['formula'] = serialize($data['formula']);

		foreach($data as $key => $d)
		{
			$data[$key] = $this->db->escape($d);
		}
		extract($data);
		
		$sql = "UPDATE `" . DB_PREFIX . "formulas` SET `name` = '$name', `formula` = '$formula', `status` = '$status' WHERE `id` = ".(int)$id;
		$this->db->query($sql);
	}

	public function deleteFormula($id) 
	{
		$sql = "DELETE FROM `" . DB_PREFIX . "formulas` WHERE `id` = ".(int)$id;
		$this->db->query($sql);
	}


}
