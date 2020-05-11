<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');
function dashboard_get_menu_left(){
	$CI = & get_instance();
	$CI->config->load('dashboard');
	$CI->config->load('dashboard_override');
	$menu_items = $CI->config->item('dashboard_tables');
	$base_url = base_url();
	$return_menu_items = array();
	if(!count($menu_items) ){
		$tables_path = FCPATH.$CI->config->item('xml_file_path');
		$menu_items = array_diff (scandir($tables_path) , array('..', '.') ); 
	}
	$controller_sub_folder = $CI->config->item('controller_sub_folder');
	$output_html='';
	$current_item= $CI->uri->segment(2);
	
	$current_user_role = get_user_role();
	
	$allowed_tables = get_user_viewable_tables($current_user_role);
	
	$is_super_admin = FALSE;
	
	if(in_array('*' , $allowed_tables)){
		$is_super_admin=1;
	}
	
	$listing_view = $CI->config->item('listing_view');
	foreach($menu_items as $menu ){
		$menu = str_replace('.xml', '', $menu);
		$single_item = new stdClass();
		
		if($is_super_admin OR ( in_array($menu, $allowed_tables)) ){				
				
				$selecte_item='';
				if($current_item==$menu){
					$selecte_item='active';
				}
				$single_item->class = "menu-anchor {$selecte_item}";
				$single_item->href = $base_url.$controller_sub_folder.'/'.$menu.'/'.$listing_view ;				
				$single_item->display_text = dashboard_lang($menu);				
				$return_menu_items[] = $single_item;
		}
	}
	return $return_menu_items;
}

/*
 * this function returns a 
 * list of table named in array 
 * which this current user 
 * can view
 */
function get_user_viewable_tables($role){
	$CI = & get_instance();
	$CI->config->load('dashboard');
	$CI->config->load('dashboard_override');
	$tableName = $CI->config->item('prefix').'table_permissions';
	
	$return_tables = array();
	$CI->db->select("distinct(`table`) as tables");
	$CI->db->where("role", $role );
	$results = $CI->db->get($tableName)->result_array();
	if(count($results)){
		foreach ($results as $result ){
			$return_tables[] = ($result['tables']);
		}
		return $return_tables;
	}else{
		return array() ; 
	}
}
