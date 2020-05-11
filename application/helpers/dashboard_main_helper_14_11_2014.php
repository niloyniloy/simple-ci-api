<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_main_helper {
	
	public static  $_instance; 
	
	public static function get_instance(){
		
		if(!isset(self::$_instance) ){
			
			self::$_instance = new Dashboard_main_helper() ; 
			
		}
		
		return self::$_instance; 
	}
	
	public function get($var){
		if( isset($this->{$var}) ){
			return $this->{$var} ;
		}else{
			return NULL;
		}
	}
	
	public function set($var , $value){
		$this->{$var} = $value; 
	}
}



/*
 * this function renders left
* menu
*/
function boo2_render_left_menu($base_url=''){
	$CI = & get_instance();
	$CI->config->load('dashboard');
	$menu_items = $CI->config->item('dashboard_tables');
	$controller_sub_folder = $CI->config->item('controller_sub_folder');
	$output_html='';
	$current_item=$CI->uri->segment(1);
	// 	$base_url .='index.php/';
	$listing_view = $CI->config->item('listing_view');
	foreach($menu_items as $menu ){
		$output_html .= "<li>";
		$selecte_item='';
		if($current_item==$menu){
			$selecte_item='selected';
		}
		$output_html .= "<a class='menu-anchor {$selecte_item}' href='".$base_url.$controller_sub_folder.'/'.$menu."/{$listing_view}'>".dashboard_lang($menu)."</a>";
		$output_html .= "</li>";
	}
	echo $output_html;
}

function boo2_render_form_label($text , $description , $position='top'){
	$html =  '<label data-toggle="tooltip" ';
	$html .= 'data-placement="'.$position.'" title="'.$description.'">' ;
	$html .= ($text) ;
	$html .= '</label>';
	return $html;
}

function boo2_render_image($src_image, $img_upload_path){
	$html =  '<div>';
	if ( ( stripos($src_image, "http://") !== false ) || ( stripos($src_image, "https://") !== false ) ) {		
		$html .= '<img width="100" src="'.$src_image.'">' ;
	}else{
		$html .= '<img width="100" src="'.base_url().$img_upload_path.$src_image.'">' ;		
	}	
	$html .= '</div>';
	return $html;
}

function boo2_render_file($src_image, $img_upload_path){
	$html =  '<div>';
	if ( ( stripos($src_image, "http://") !== false ) || ( stripos($src_image, "https://") !== false ) ) {
		$html .= '<a href="'.$src_image.'">'.$src_image.'</a>' ;
	}else{
		$html .= '<a href="'.base_url().$img_upload_path.$src_image.'">'.$src_image.'</a>' ;
	}	
	$html .= '</div>';
	return $html;
}
/*
 * this function renders form for add/edit data
*/
function boo2_render_form($xmlObject ,  $selected=''){
	$CI = & get_instance();
	$type = (string) $xmlObject['type'] ;
	$name = (string) $xmlObject['name'];
	$description = (string) $xmlObject['description'];
	$lookup_default_value = (string) $xmlObject['default_value'];

	$outupt_html = "";
	if($type=='hidden'){
		
	}else{
		$outupt_html .= boo2_render_form_label( dashboard_lang($name ), dashboard_lang($description ));
		if(($type == 'image') && ($selected != '')){
			$img_upload_path = $CI->config->item('img_upload_path');
			$outupt_html .= boo2_render_image($selected, $img_upload_path);
		}elseif(($type == 'file') && ($selected != '')){
			$img_upload_path = $CI->config->item('img_upload_path');
			$outupt_html .= boo2_render_file($selected, $img_upload_path);				
		}else{
			
		}
	}


	$type = $xmlObject['type'] ;
	$required = $xmlObject['required'] ;
	$name = (string)$xmlObject['name'];


	switch ($type) {

		case "input":

			$data = array(
			'name'        => $name,
			'id'          => $name,
			'class'   => 'form-control'
					);
					$show_currency = (integer) $xmlObject['show_currency_symbol'];
					
					if($show_currency > 0){
						$outupt_html .= '<div class="input-group">';
					}
					$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
					$selected_value = "";
					if($selected == ""){
						$selected_value = (string) $xmlObject['default_value'];
					}else{
						$selected_value = (string)$selected;
					}
					
					if($show_currency > 0){
						$outupt_html .= '<span class="input-group-addon">'.$CI->config->item('default_currency_symbol').'</span>';
						
						$outupt_html .= form_input($data, $selected_value, $extra);
						
						$outupt_html .= '</div>';
					}else{
						$outupt_html .= form_input($data, $selected_value, $extra);
					}
					
					
						
			break;
			
		case "auto":
			$options = array();
			$options[""] = dashboard_lang('_SELECT_FROM_DROPDOWN');
				
			$data = array(
			'name'        => $name,
			'id'          => 'selectid',
			'class'   => 'form-control select2'					
					);
			$extra = "type='select'";				

			$outupt_html .= form_input($data, (string)$selected, $extra);
		
			break;			

		case "textarea":

			$rows = (int) $xmlObject['rows'];
				
			$data = array(
					'name'        => $name,
					'id'          => $name,
					'class'   => 'form-control',
					'rows'    => 	($rows != "")? $rows : "10"
			);

			$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
			$outupt_html .= form_textarea($data, (string)$selected, $extra);
				
			break;
		case 'editor':
				
			$db_helper = Dashboard_main_helper::get_instance();
			$db_helper->set('load_ckeditor' , 1 );
				
			$data = array(
					'name'        => $name,
					'id'          => $name,
					'class'   => 'ckeditor'
			
			);
			$outupt_html .= form_textarea($data, (string)$selected);
			break;
		case 'color' :
			$db_helper = Dashboard_main_helper::get_instance();
				
			$db_helper->set('load_colorpicker' , 1 );
				
			$data = array(
					'name'        => $name,
					'id'          => $name,
					'class'   => 'form-control colorpicker'
			);
			$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
			$outupt_html .= form_input($data, (string)$selected, $extra);
				
			break;
				
		case "radio":
			$total_options = ($xmlObject->count());

			for($i=0 ; $i<$total_options ; $i++ ){
				$attributes = ($xmlObject->option[$i]->attributes());
				if($selected == (string) $attributes['key']){
					$checked=true;
				}else{
					$checked=false;
				}
				$data = array(
						'name'        => $name,
						'id'          => $name,
						'value'       => (string) $attributes['key'],
						'checked'     => $checked
				);
				$outupt_html .= '<div class="radio">';
				$outupt_html .=  '<label>'.form_radio($data).nbs().dashboard_lang((string)$xmlObject->option[$i]).'</label>';
				$outupt_html .= "</div>";
			}

			break;
				
		case "select":
			$options = array();
			$options[$lookup_default_value] = dashboard_lang('_SELECT_FROM_DROPDOWN');
			$total_options = ($xmlObject->count());
			for($i=0 ; $i<$total_options ; $i++ ){
				$attributes = $xmlObject->option[$i]->attributes();
				$options[(string)$attributes['key']] = dashboard_lang((string) $xmlObject->option[$i]);
			}
			$extra = "id='{$name}' class='{$name} form-control dashboard-dropdown'";
			if(isset($required) && intval($required) > 0){
				$extra .= "required='true'";
			}

			$outupt_html .=  form_dropdown($name , $options , $selected , $extra);
				
			break;
				

		case "lookup":
			$options = array();
			$options[$lookup_default_value] = dashboard_lang('_SELECT_FROM_DROPDOWN');
			$ref_table=  (string) $xmlObject['ref_table'];
			$key = (string) $xmlObject['key'];
			$value = (string) $xmlObject['value'];
			$orderBy = (string) $xmlObject['order_by'] ? (string) $xmlObject['order_by'] : $value;
			$orderOn = (string) $xmlObject['order_on'] ? (string) $xmlObject['order_on'] : 'ASC';				
			$autosuggest = (int) $xmlObject['autosuggest'];

			if(isset($autosuggest) && $autosuggest == 1){

				$data = array(
						'name'        => $name,
						'id'          => $name,
						'ref_table'	  => $ref_table,
						'key'         => $key,
						'val'         => $value,						
						'class'       => 'form-control select2'
				);
				
				$extra = "id='selectid' class='{$name} form-control select2'";
				
				if(isset($required) && intval($required) > 0){
					$extra .= "required='true'";
				}
				
				$outupt_html .= form_input($data, (string)$selected, $extra);
				
			}else{

				$tableName = $CI->config->item("prefix").$ref_table;
				
				$query = $CI->dashboard_model->lookup($tableName, $key, $value, $orderBy, $orderOn);
				
				foreach($query->result() as $row){
					$options[$row->{$key}] = $row->{$value};
				}
				
				$extra = "id='{$name}' class='{$name} form-control dashboard-dropdown'";
				
				if(isset($required) && intval($required) > 0){
					$extra .= "required='true'";
				}
				
				$outupt_html .=  form_dropdown($name , $options , $selected , $extra);
											
			}
				
			break;

		case "datetime":
				
			$data = array(
			'name'        => $name,
			'id'          => $name,
			'class'   => 'form-control dashboard_datetime'
					);
					$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
					$outupt_html .= form_input($data, (string)$selected, $extra);

					break;

		case "image":

			$data = array(
			'name'        => $name,
			'id'          => $name,
			'class'   => ''
					);
			if($selected == ""){
				$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
			}else{
				$extra = "";
			}				
					$outupt_html .= form_upload($data, (string)$selected, $extra);

					break;
						
		case "file":

			$data = array(
			'name'        => $name,
			'id'          => $name,
			'class'   => ''
					);
			if($selected == ""){
				$extra = (isset($required) && intval($required) > 0) ? 'required="true"' : '';
			}else{
				$extra = "";
			}					
					$outupt_html .= form_upload($data, (string)$selected, $extra);

					break;
		case 'hidden':
					
			$outupt_html .= form_hidden($name, (string)$selected);
			
			break;
						
		default:
			$outupt_html =  "";
	}

	return $outupt_html;

}

/*
 * Span render if no need to edit field
*/
function boo2_render_span($xmlObject, $selected = ""){
	$CI = & get_instance();
	$type = (string) $xmlObject['type'] ;
	$name = (string) $xmlObject['name'];
	$description = (string) $xmlObject['description'];

	$output_html = "";
	if($type=='hidden'){

	}else{
		$output_html .= boo2_render_form_label( dashboard_lang($name ), dashboard_lang($description ));
		if(($type == 'image') && ($selected != '')){
			$img_upload_path = $CI->config->item('img_upload_path');
			$output_html .= boo2_render_image($selected, $img_upload_path);
		}
	}

	switch ($type) {

		case "hidden":
			$output_html = "";
			break;
				
		case "lookup":
			$options = array();
			$ref_table=  (string) $xmlObject['ref_table'];
			$key = (string) $xmlObject['key'];
			$value = (string) $xmlObject['value'];
				
			$tableName = $CI->config->item("prefix").$ref_table;
				
			$query = $CI->dashboard_model->lookup($tableName, $key, $value);
				
			foreach($query->result() as $row){
				$options[$row->{$key}] = $row->{$value};
			}
				
			$output_html .=  "<br/><span class='form-span'> ".(string) @$options[$selected] . "</span>";
				
			break;
				
		case "select":
			$options = array();
				
			$total_options = ($xmlObject->count());
			for($i=0 ; $i<$total_options ; $i++ ){
				$attributes = $xmlObject->option[$i]->attributes();
				$options[(string)$attributes['key']] = (string) $xmlObject->option[$i];
			}

			$output_html .=  "<br/><span class='form-span'> ".(string) @$options[$selected] . "</span>";

			break;
				
		default:
			$output_html .=  "<br/><span class='form-span'> ".(string) @$selected . "</span>";

	}
	return $output_html;

}

/*
 * User id return
*/
function get_user_id(){
	$CI = & get_instance();

	$user_id_config = $CI->config->item('user_id');

	$user_id = $CI->session->userdata($user_id_config);

	return $user_id;

}

/*
 * User role
*/

function get_user_role(){

	$CI = & get_instance();

	$user_role_config = $CI->config->item('user_role');

	$user_role = $CI->session->userdata($user_role_config);

	return $user_role;
}

/*
 * User role permission for copy, delete
*/

function get_permission_role(){

	$CI = & get_instance();

	$selectField = '`role`, `table`';
	
	
	$tableName = $CI->config->item('prefix') . 'permissions_row';
	
	$whereSupperUser = array('`role`' => get_user_role(), '`table`' => '*');
	
	$user_role_permission = $CI->dashboard_model->getTableRows($tableName, $selectField, $whereSupperUser);

	if($user_role_permission){
		
		return $user_role_permission;
		
	}else{
		
		$whereArray = array('`role`' => get_user_role(), '`table`' => $CI->current_class_name);
		
		$user_role_permission = $CI->dashboard_model->getTableRows($tableName, $selectField, $whereArray);
		
		return $user_role_permission;		
		
	}	

	
}


/*
 * show current version
*/
function show_current_version(){
	$xml_path = FCPATH.APPPATH.'core/dashboard.xml';
	if(file_exists($xml_path) ){
		$xml_data = simplexml_load_file($xml_path);
		echo '<small>'.lang('_DASHBOARD_VERSION_CURRENT').($xml_data->version).'</small> <a href="'.base_url().'upgrade/confirm">'.lang('_DASHBOARD_VERSION_UPGRADE').'</a>';
	}
}

/*
 * Render dashboard language
*/
function dashboard_lang($string = ''){
	$string = trim($string);
	$langtext =  lang($string);
	if(strlen($langtext)){
		return $langtext;
	}
	return $string;
}

/*
 * render top action part
*/
function boo2_render_aditional( $class) {
	$data [''] = '';
	return $CI->load->view ( $class .'/'.$class.'_additional', $data );
}

/*
 * render top action part
*/
function boo2_render_top( $additional_buttons = array()) {
	$CI = get_instance ();
	$dashboard_helper= Dashboard_main_helper::get_instance();
	$dashboard_helper->get('edit_path');
	$data ['site_url'] = site_url();
	$data ['edit_path'] = $dashboard_helper->get('edit_path');
	$data ['additional_buttons'] = $additional_buttons;
	return $CI->load->view ( 'core/helper_render_action', $data );
}

/*
 * render edit top action part
*/
function boo2_render_edit_top() {
	$CI = get_instance ();
	$dashboard_helper= Dashboard_main_helper::get_instance();	
	$data ['id'] = @$dashboard_helper->get('id');
	$data ['base_url'] = base_url();	
	$data ['edit_path'] = $dashboard_helper->get('edit_path');
	return $CI->load->view ( 'core/helper_render_edit_button', $data );
}

/*
 * check if view exists
 */
function dashboard_view_exists($view_name) {
	$file_path = FCPATH . APPPATH . 'views/' . $view_name . '.php';
	return file_exists ( $file_path );
}
/*
 * require a view 
 */
function dashboard_load_view($view_name){
	$file_path = FCPATH . APPPATH . 'views/' . $view_name . '.php';
	if( file_exists ( $file_path )){
		require_once $file_path ;
	}
}
