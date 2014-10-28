<?php
/*
 *
 * Model Class for table article
 * generated on 28 October 2014 13:54:29
 *
 *
 * This file is auto generated by Akane Console Tools
 * you can customize it to your need
 * for more information
 * type command "php console" from Akane directory on Terminal console
 * 
 */
class article
{
	var $main;
	
	function __construct() {
		$this->main = get_instance();
	}
	
	function single($id){
		return $this->main->db->get_data('article', '', "id='$id'");
	}
	
	function name($id){
		$data = $this->main->db->get_data('article', '', "id='$id'");
		return $data[0]['name']; # change this to field that contain name
	}

	function all($limit='',$keyword=''){
		$where = '';
		if ($keyword!=''){
			# please change this searchable column name to your need
			$where = "%searchable_column% LIKE '%$keyword%'";
			$searchable = array('id','judul','isi','tags','publish');

			if (count($searchable) > 1){
				$wheres = array();
				foreach ($searchable as $field){
					$wheres[] = $field." LIKE '%".$keyword."%'";
				}
				$where = implode(' OR ',$wheres);
			} else {
				$where = $searchable[0]." LIKE '%".$keyword."%'";
			}
		}
		$data = $this->main->db->get_data('article', '', $where, '', $limit);
		return $data;
	}	
}
