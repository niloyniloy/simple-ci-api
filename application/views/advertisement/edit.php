<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$base_url = CDN_URL;
$site_url = site_url ();
$controller_sub_folder = $this->config->item('controller_sub_folder');
$dashboard_helper = Dashboard_main_helper::get_instance ();
$dashboard_helper->set( 'edit_path', $edit_path );
$dashboard_helper->set( 'id', @$id );
$all_permission_given = $dashboard_helper->get('supper_user');
$supper_user_edit_permission = $dashboard_helper->get('supper_user_edit_permission');
?>
<link href="<?php echo CDN_URL; ?>dashboardmedia/css/select2.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<link href="<?php echo CDN_URL; ?>dashboardmedia/css/edit.css" rel="stylesheet">

<script type="text/javascript">
var baseURL = "<?php echo $base_url; ?>";
var siteURL = "<?php echo $site_url; ?>"
var userId = "<?php echo get_user_id(); ?>";
var controller_name = "<?php echo $class_name ; ?>";
var controller_sub_folder = "<?php echo $controller_sub_folder; ?>";
var selectItemCheckbox = "<?php echo dashboard_lang("SELECT_ITEM_CHECK_BOX_ALERT") ?>";
var confirmDeleteAlert = "<?php echo dashboard_lang("DELETE_CONFIRM_ALERT") ?>";
var confirmCopyAlert = "<?php echo dashboard_lang("COPY_CONFIRM_ALERT") ?>";
</script>
<div id="ajax_load" style="display: none">
	<img src="<?php echo $base_url; ?>dashboardmedia/img/ajax-loader.gif" />
</div>
<form role="form" method="post" action="" enctype="multipart/form-data">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
		<h1 class="page-header">
		
		<?php echo dashboard_lang('_DASHBOARD_LISTING_PAGE_ITEM') ; echo dashboard_lang($form_name); ?>
		<a href="<?php  echo $base_url."index.php/". $controller_sub_folder.'/'.$listing_path; ?>" class="btn btn-primary">
			<?php echo dashboard_lang('_DASHBOARD_LISTING_PAGE_BACK'); ?>
		</a>
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div><!--End Heading row -->


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="button_row">
<?php echo $this->load->view($viewPathAction, array('base_url' => $base_url , 'edit_path'=>$edit_path, 'id' => @$id ) ) ;?>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div><!--End button row -->








<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">

			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
					<!-- 
						<form role="form" method="post" action="" enctype="multipart/form-data">							
							-->
							<div class="row">
								<div class="col-lg-6">
			
									<?php
									
									$groupData = array();
									
									foreach($data_load->field as $value){										
		
										if(isset($value['group']) && $value['group']){
		
											$groupData[(string)$value['group']][] = $value;
		
										}else{
										
											$valueType = (string) $value["type"];
											$nameField = (string) $value["name"];
											if($valueType != 'hidden'){
												echo '<div class="form-group '. 'field_'.$nameField.'" id ="'.'field_id_'.$nameField.'">';
											}											
											
												if( array_key_exists($nameField, $edit_field_array)){

													if($edit_field_array[$nameField]){

														echo boo2_render_form($value, @$data_edit[(string)$value["name"]]);
														
													}else{
														
														echo boo2_render_span($value, @$data_edit[(string)$value["name"]]);
													}													
														
												}else if($all_permission_given){
													
													if($supper_user_edit_permission){
													
															echo boo2_render_form($value, @$data_edit[(string)$value["name"]]);																	
															
													}else{
															echo boo2_render_span($value, @$data_edit[(string)$value["name"]]);
															
													}											
													
												}				 							
											if($valueType != 'hidden'){
				 								echo "</div>";
			 								}
										}
									}
		
									$data['all_permission_given'] = $all_permission_given;
									$data['supper_user_edit_permission'] = $supper_user_edit_permission;
									$data['tab_data'] = $groupData;
									$data['data_edit'] = @$data_edit;
									$data['edit_field_array'] = $edit_field_array;

									?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
								<?php 
								
									$this->load->view('core/edit_tab', $data );
								
								?> 
								</div>
							</div>
							<?php if(isset($view_additional) && $view_additional):?>
							<div class="row">
								<div class="col-lg-6">
								<?php 
								
									$this->load->view($view_additional);
								
								?> 

								</div>							
							<?php endif;?>
							<div class="row">
							  <div class="col-lg-6">
							    <div id="overview_category_section">
							    <label>City</label>
							    <select name="city[]" class="form-control dashboard-dropdown" multiple>
                                   <?php echo render_all_city_options( $id ); ?>
							    </select>
							    </div>
							    </div>
							</div> 
							<!-- 
								<button type="submit" class="btn btn-default"><?php echo dashboard_lang('_CORE_EDIT_SAVE_BUTTON');?></button>
								 -->
						
					
					</div>

				</div>
				<!-- /.row (nested) -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
</form>

<?php if ($data_edit['is_paid'] == '1' && strlen($id) > '0') { ?>

<div class="row">
	<div class="col-lg-6">
		 <div id="overview_category_section">
               <label>Payment Status: <span style='color:green'> Paid </span></label>
		</div>
	 </div>
</div> 

<div class="row">
	<div class="col-lg-6">
		 <div id="overview_category_section">
                  &nbsp;
		</div>
	 </div>
</div> 

<?php } ?>

<?php if ($data_edit['is_paid'] == '0' && strlen($id) > '0') { ?>
<div class="row">
	<div class="col-lg-6">
		 <div id="overview_category_section">
	         <form action="<?php echo base_url(); ?>dbtables/advertisement/do_payment?add_id=<?php echo $id;?>&email=<?php echo get_add_email( $id );?>" method="post">
										  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
										          data-key="<?php echo $publishable_key; ?>"
										          data-amount="<?php echo get_add_total_amount( $id );?>" data-description="Advertisement Payment"></script>
			 </form>
		</div>
	 </div>
</div> 

<div class="row">
	<div class="col-lg-6">
		 <div id="overview_category_section">
                  &nbsp;
		</div>
	 </div>
</div> 
<div class="row">
	<div class="col-lg-6">
		 <div id="overview_category_section">
                  &nbsp;
		</div>
	 </div>
</div> 
<?php } ?>
<!-- /.row -->
<?php 
$db_helper = Dashboard_main_helper::get_instance();
if( $db_helper->get('load_ckeditor') ){
	echo  '<script type="text/javascript" src="'.$base_url.'dashboardmedia/ckeditor/ckeditor.js"></script> ';
}
if( $db_helper->get('load_colorpicker') ){
	echo '<link href="'.$base_url.'dashboardmedia/css/bootstrap-colorpicker.min.css" rel="stylesheet">'; 
	echo  '<script type="text/javascript" src="'.$base_url.'dashboardmedia/js/bootstrap-colorpicker.js"></script> ';
	echo '<script type="text/javascript">jQuery(document).ready(function(){         jQuery(".colorpicker").colorpicker({format:"hex"});     } );</script> ';
}
?>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="<?php echo $base_url ?>dashboardmedia/js/select2.js"></script>
<script src="<?php echo $base_url ?>dashboardmedia/js/edit.js"></script>

<?php

$base_url = $base_url."index.php/";

?>
<script type="text/javascript">
var id = parseInt("<?php echo $id;?>");
if (isNaN(id)) {

}else {
	$("#plan_id").attr("disabled", "disabled");
}

$("#field_id_is_paid").remove();
$("#field_id_total_viewed").remove();
$("#field_id_total_clicked").remove();
$("#field_id_ads_city").remove();

jQuery(document).ready(function(){

	$("#field_id_is_paid").remove();
	$("#field_id_total_viewed").remove();
	$("#field_id_total_clicked").remove();
	$("#field_id_ads_city").remove();
	
	$(".select2").select2({
	    placeholder: "<?php echo dashboard_lang('_DASHBOARD_EDIT_PLEASE_SELECT')?>",
	    minimumInputLength: 1,
        ajax: {
            url: baseURL + controller_sub_folder +'/'+controller_name+'/get_lookup_auto_suggest',
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term,
                    tbl: $(this).attr('ref_table'),
                    key: $(this).attr('key'),
                    val: $(this).attr('val'),
                    page_limit: 10,
                    apikey: "ju6z9mjyajq2djue3gbvv26t"
                  };
            },
            results: function (data, page) {

            	 var newData = [];
            	    $.each(data, function (index,value) {
            	        newData.push({
            	            id: value.id,  //id part present in data
            	            text: value.name  //string to be displayed
            	        });
            	    });

            	    return { results: newData };
            }
        },
	    initSelection: function(element, callback) {
	    	// the input tag has a value attribute preloaded that points to a preselected movie's id
	    	// this function resolves that id attribute to an object that select2 can render
	    	// using its formatResult renderer - that way the movie name is shown preselected
	    	var lookup_id = element.attr('id');
	    	var id=$(element).val();
	    	if (id!=="") {
		    	$.ajax(baseURL + controller_sub_folder +'/'+controller_name+'/get_lookup_auto_suggest', {
		    	data: {
		    	id: id,
                tbl: $('#'+lookup_id).attr('ref_table'),
                key: $('#'+lookup_id).attr('key'),
                val: $('#'+lookup_id).attr('val'),
		    	apikey: "ju6z9mjyajq2djue3gbvv26t"
		    	},
		    	dataType: "json"
		    	}).done(function(data) {			    	
	            	 var selectData = [];
	            	    $.each(data, function (index,value) {
	            	    selectData.push({
	            	            id: value.id,  //id part present in data
	            	            text: value.name  //string to be displayed
	            	        });
	            	    });
			    	 callback(selectData[0]); 
			    	 });
	    	}
    	},        

  	});

    $("#copy").on("click", function(){
		   
        var checkAllValues = new Array('<?php echo @$id;?>');

        if(checkAllValues.length == 0){
        	alert(selectItemCheckbox);
        	return false;
        }
        else{

            var confirmCopy = confirm(confirmCopyAlert);
            
            if (confirmCopy == true) {

            	$('#ajax_load').show();

                $.ajax({
                	url:"<?php echo $base_url.$controller_sub_folder.'/'.$class_name .'/'.$copy_method; ?>",
                    type: 'post',
                    data: {ids: checkAllValues },
                    success:function(result){
                    	$('#ajax_load').hide();                    
                    	window.location.href = "<?php echo $base_url.$controller_sub_folder.'/'.$listing_path; ?>";
                  	}
	             });
                
            } else {
                return false;
            }

        }

	    
    }); 

    $("#delete").on("click", function(){

        var checkAllValues = new Array('<?php echo @$id;?>');

        if(checkAllValues.length == 0){
        	alert(selectItemCheckbox);
        	return false;
        }else{

            var confirmDelete = confirm(confirmDeleteAlert);
            
            if (confirmDelete == true) {

            	$('#ajax_load').show();

                $.ajax({
                	url:"<?php echo $base_url.$controller_sub_folder.'/'.$class_name .'/'.$delete_method; ?>",
                    type: 'post',
                    data: {ids: checkAllValues },
                    success:function(result){
                    	$('#ajax_load').hide();                    
                    	window.location.href = "<?php echo $base_url.$controller_sub_folder.'/'.$listing_path; ?>";
                  	}
	             });
                
            } else {
                return false;
            }

        }
	    
    });     

   

    $(":password").each(function(){
        
    	$(":password").attr("autocomplete","off");
    	$(this).attr("required","required");
        $(this).parent().clone().insertAfter($(this).parent());
        $(this).parent().next().attr("id","field_id_re_password");
        $(this).parent().next().children().text("<?php echo dashboard_lang('PASSWORD_CHANGE_RETYPE_PASSWORD'); ?>");
        $(this).parent().next().find(":password").attr("id","re_password").attr("name","re_password");
        $(":password").val("");

    });

    $(":submit").on("click",function(){

    	var pass = $('form').find(':password').filter(':visible:first');
    	pass = pass[0].value;

    	var re_pass = $('#re_password').val();
        var msg = "<?php echo dashboard_lang("TWO_PASSWORD_MUST_MATCH"); ?>";
    	if(pass == re_pass){
    	   

        }else{
            
          alert(msg);
          return false;

        }

    });
    
	
});
</script>
