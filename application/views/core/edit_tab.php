<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$first_time=true;
?>

<!-- /.panel-heading -->
<div class="panel-body">
	<!-- Nav tabs -->
	
	
<div class="row">
	<div class="col-lg-12">	

	<ul class="nav nav-tabs">
	
		<?php 
		$output_tab_div = "";

		foreach($tab_data as $key => $value){
			
			$class = ($first_time)?' in active':'' ;
			$key_id = str_replace(" ", "-", trim($key));
			$output_tab_div .= '<div class="tab-pane fade '.$class.'" id="dashboard_' . $key_id . '">';
			

?>


		<li class="<?php echo $class; ?>"><a href="#dashboard_<?php echo $key_id; ?>" data-toggle="tab"><?php echo dashboard_lang($key); ?>
		</a>
		</li>

		<?php 	
		$first_time=false;
			foreach($value as $object){

				$nameField = (string)$object["name"];
				$feildType = (string)$object["type"];

				if($feildType != 'hidden'){
					$output_tab_div .= '<div class="form-group '.'field_'.$nameField.'" id ="'.'field_id_'.$nameField.'">';
				}
				
				
				if( array_key_exists($nameField, $edit_field_array)){

					if($edit_field_array[$nameField]){
				
						$output_tab_div .= boo2_render_form($object, @$data_edit[(string)$object["name"]]);
				
					}else{
				
						$output_tab_div .= boo2_render_span($object, @$data_edit[(string)$object["name"]]);
					}
				
				}else if($all_permission_given){
						
					if($supper_user_edit_permission){
							
						$output_tab_div .= boo2_render_form($object, @$data_edit[(string)$object["name"]]);
							
					}else{
						$output_tab_div .= boo2_render_span($object, @$data_edit[(string)$object["name"]]);
							
					}
						
				}
				if($feildType != 'hidden'){
					$output_tab_div .= "</div>";
				}
			}

			$output_tab_div .= "</div>";

}

?>
	</ul>

	
	</div>
</div>

<div class="row">
	<div class="col-lg-6">

		<div class="tab-content">
			<?php  echo $output_tab_div; ?>
		</div>
		
	</div>
</div>
	
</div>	
	<!-- /.panel-body -->
	

	
	
