<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
$perPegeArray = $this->config->item ( "list_per_page" );
$base_url = CDN_URL;
$site_url = site_url ();
$controller_sub_folder = $this->config->item ( 'controller_sub_folder' );
$img_upload_path = $this->config->item ( 'img_upload_path' );
$dashboard_helper = Dashboard_main_helper::get_instance ();
$dashboard_helper->set ( 'edit_path', $edit_path );
?>
<link href="<?php echo CDN_URL; ?>dashboardmedia/css/listing.css" rel="stylesheet">
<script type="text/javascript">
	var baseURL = "<?php echo $base_url; ?>";
	var siteURL = "<?php echo $site_url;?>";
	var controllerFolder = "<?php echo $controller_sub_folder;?>";
	var controller_name = "<?php echo $table_name ; ?>";
	var userId = "<?php echo get_user_id(); ?>";
	var selectItemCheckbox = "<?php echo dashboard_lang("SELECT_ITEM_CHECK_BOX_ALERT") ?>";
	var confirmDeleteAlert = "<?php echo dashboard_lang("DELETE_CONFIRM_ALERT") ?>";
	var confirmCopyAlert = "<?php echo dashboard_lang("COPY_CONFIRM_ALERT") ?>";
	var search_auto_suggest_limit = "<?php echo $this->config->item('search_auto_suggest_limit') ; ?>";
</script>
<div id="ajax_load" style="display: none">
	<img
		src="<?php echo $base_url; ?>dashboardmedia/img/ajax-loader.gif"
		alt="" />
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<h1 class="page-header">
			<?php echo dashboard_lang($table_name); ?>
		</h1>
	</div>
</div>
<!-- End Heading -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">

			<div class="panel-heading">
				<?php echo dashboard_lang('_LISTING_TABLE_DATA') . dashboard_lang($table_name) ;?>
			</div>
			<!--End panel-heading-->

			<div class="panel-body">

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php echo $this->load->view($viewPathAction, array('site_url' => $site_url , 'edit_path'=>$edit_path ) ) ;?>
					</div>
				</div>
				<!--End add edit delete button-->


				<div class="row padding_bottom_15px">

					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<form name="per_page_form" id="per_page_form" action=""
							method="post">
							<label> <select name="per_page" id="per_page"
								class="form-control input-sm">
									<?php foreach($perPegeArray as $key=>$value ){ ?>
									<option value="<?php echo $key; ?>"
										<?php if($key == $per_page_show) echo "selected";  ?>>
										<?php echo $value; ?>
									</option>
									<?php } ?>
							</select>
							</label>
							<?php
							if($total_items_count == 1){

								$listing_per_page = dashboard_lang('_LISTING_ITEM_PER_PAGE');

							}else{

								$listing_per_page = dashboard_lang('_LISTING_ITEMS_PER_PAGE');

							}
							printf ( $listing_per_page , $total_items_count ); ?>
						</form>
					</div>
					<!--End linsting-->


					<div class="col-lg-8 col-md-8 col-sm-18 col-xs-12">
						<div class="pull-right">
							<div class="show_fields">

								<a href="#" type="text" data-toggle="modal"
									data-target="#show_checkbok_field">
									<?php echo dashboard_lang('_DASHBOARD_LISTING_SHOW_FIELDS'); ?>
								</a>

								<a href="#" type="text" data-toggle="modal"
									data-target="#order_checkbok_field">
									<?php echo dashboard_lang('_DASHBOARD_ORDER_BY_FIELDS'); ?>
								</a>

								<div id="show_checkbok_field" class="modal fade" role="dialog"
									aria-hidden="true" aria-labelledby="myModalLabel">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">&times;</span><span
														class="sr-only"><?php echo dashboard_lang('_DASHBOARD_LISTING_MODAL_CLOSE'); ?></span>
												</button>
												<h4 class="modal-title" id="myModalLabel">
													<?php echo dashboard_lang('_DASHBOARD_LISTING_SHOW_FIELDS_TITLE'); ?>
												</h4>
											</div>
											<div class="modal-body dashboard_modal_body">
                                            	<div class="list_wrapper">
                                                    <label><?php echo dashboard_lang('_DASHBOARD_SELECT_FIELDS_LABEL_LIST'); ?></label>
                                                    <ul id="sortable1" class="droptrue">
                                                        <?php

                                                        if (isset ( $all_field ) && count ( $all_field ) > 0) :

                                                            foreach ( $all_field as $field ) {

																if($field ['type'] != "hidden"){
                                                                	if (! in_array ( ( string ) @$field ['name'], $listing_field )) :
                                                                    ?>
                                                        <li class="ui-state-default"
                                                            data-value="<?php echo (string) @$field['name']; ?>"><?php echo dashboard_lang((string) @$field['name']); ?>
                                                        </li>



                                                                <?php endif;
                                                                }
                                                            }



                                                         endif;
                                                        ?>
                                                    </ul>
                                                </div>

												<div id="div_show_field" class="list_wrapper">
													<label><?php echo dashboard_lang('_DASHBOARD_FIELDS_SHOWN_LABEL_LIST'); ?></label>
													<ul id="sortable2" class="dropfalse">
														<?php

														if (isset ( $all_field ) && count ( $all_field ) > 0) :

															foreach ( $listing_field as $field ) :
																?>
														<li class="ui-state-highlight"
															data-value="<?php echo $field; ?>"><?php echo dashboard_lang($field); ?>
														</li>
														<?php
															endforeach
															;



						                                endif;
														?>
													</ul>
												</div>

												<div style="clear: both;"></div>
												<br />
												<button type="submit" name="show_field_add"
													id="show_field_add" class="btn btn-default">
													<?php echo dashboard_lang('_DASHBOARD_LISTING_BUTTON_SAVE'); ?>
												</button>

												<button type="submit" name="show_field_reset"
													id="show_field_reset" class="btn btn-default">
													<?php echo dashboard_lang('_DASHBOARD_LISTING_BUTTON_RESET'); ?>
												</button>

												<?php // endif; ?>
											</div>
										</div>
									</div>
								</div>

								<div id="order_checkbok_field" class="modal fade" role="dialog"
									aria-hidden="true" aria-labelledby="myModalLabel">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">&times;</span><span
														class="sr-only">Close</span>
												</button>
												<h4 class="modal-title" id="myModalLabel">
													<?php echo dashboard_lang('_DASHBOARD_LISTING_ORDER_FIELDS_TITLE'); ?>
												</h4>
											</div>
											<div class="modal-body">
                                            	<div class="list_wrapper">
                                                    <label><?php echo dashboard_lang('_DASHBOARD_SELECT_FIELDS_LABEL_ORDER'); ?></label>
                                                    <ul id="sortable_order" class="droptrue_order">
                                                        <?php

                                                        if (isset ( $all_field ) && count ( $all_field ) > 0) :

                                                            foreach ( $all_field as $field ) {
                                                                if($field ['type'] != "hidden"){
                                                                	if (! in_array ( $field ['name'], $ordering_fields )) :
                                                                    ?>
                                                        <li class="ui-state-default"
                                                            data-value="<?php echo (string) $field['name']; ?>"><?php echo dashboard_lang((string) $field['name']); ?>
                                                        </li>


                                                                <?php 	endif;
                                                                }
                                                            }



                                                        endif;
                                                        ?>
                                                    </ul>
                                                </div>
												<div id="div_order_field" class="list_wrapper">
												<label><?php echo dashboard_lang('_DASHBOARD_FIELDS_SHOWN_LABEL_ORDER'); ?></label>
													<ul id="sortable_order2" class="dropfalse_order">
														<?php

														if (isset ( $all_field ) && count ( $all_field ) > 0) :

															foreach ( $ordering_fields as $field ) :
																?>
														<li class="ui-state-highlight"
															data-value="<?php echo $field; ?>"><?php echo dashboard_lang($field); ?>
														</li>
														<?php
															endforeach
															;


                               							 endif;
														?>
													</ul>
												</div>

												<div style="clear: both;"></div>
												<div class="buttion_wrapper">
                                                    <button type="submit" name="order_field_add"
                                                        id="order_field_add" class="btn btn-default">
                                                        <?php echo dashboard_lang('_DASHBOARD_LISTING_BUTTON_SAVE'); ?>
                                                    </button>

                                                    <button type="submit" name="order_field_reset"
                                                        id="order_field_reset" class="btn btn-default">
                                                        <?php echo dashboard_lang('_DASHBOARD_LISTING_BUTTON_RESET'); ?>
                                                    </button>
                                                </div>

												<?php // endif; ?>
											</div>
										</div>
									</div>
								</div>

							</div>
							<!--End show fields-->

							<div class="search_box">
								<form name="search_text_form" class="search_text_form" action=""
									method="post">
									<input class="form-control input-sm typeahead" type="search"
										name="search" id="searchtext"
										value='<?php echo @$search; ?>'
										placeholder="<?php  echo dashboard_lang('_DASHBOARD_SEARCH_DEFAULT_TEXT'); ?>"
										autocomplete="off" />

									<!--strat search icon-->

									<button type="submit" class="btn btn-default">
										<i class="fa fa-search"></i>
									</button>

									<!--end search icon-->


									<button type="submit" name="reset" id="reset"
										class="btn btn-primary" <?php if(!strlen($search) ){ ?>
										style="display: none;" <?php } ?> value="1">
										<?php echo dashboard_lang('_DASHBOARD_SEARCH_RESET'); ?>
									</button>
								</form>
							</div>
							<!--End search-->
						</div>
					</div>

				</div>
				<!--End search row-->




				<div class="row padding_bottom_15px">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<table width="100%"
								class="table table-striped dataTable table-bordered table-hover"
								id="table-listing-items">
								<thead>
									<tr role="row">
										<th width="4%"><input type="checkbox" id="selectall" /></th>
										<?php
										if(is_array($listing_field) && count($listing_field) > 0){
											$filedCount = count($listing_field);
											$fieldWidth = (int) (96 / $filedCount);
										}
										foreach ( $listing_field as $field ) {
											if ($field == $sorting_options ['table_sort_field']) {
												if (strtolower ( $sorting_options ['table_sort_direction'] ) == 'asc') {
													$next_sorting = 'desc';
												} else {
													$next_sorting = 'asc';
												}
												$current_class = "sorting_" . $sorting_options ['table_sort_direction'];
											} else {
												$next_sorting = 'asc';
												$current_class = "sorting";
											}
											?>
										<th width="<?php echo $fieldWidth; ?>%"
											class="<?php echo $current_class;?> table-header-for-sort"
											data-next="<?php echo $next_sorting?>" rowspan="1"
											colspan="1"
											data-title="<?php echo ($field); ?>"><?php echo dashboard_lang($field); ?>
										</th>

										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									echo " <form id='multi_select_form' method='post' action=''>";
									echo "<tr><td></td>";
									$multi_select_array = $this->session->userdata('multi_select_array');
									$CI = & get_instance();
									$prefix = $CI->config->item("prefix");
									foreach ( $listing_field as $field ) {
									    if($all_field[$field]['multi_select'][0] == 1 ){


									        $ref_table =  @$all_field[$field]["ref_table"];
									        $ref_table_col_name =  @$all_field[$field]["value"];
									        if($ref_table){
									            $field_name = $field;

									        }else{
									            $field_name = "`".$prefix.$table_name."`.`".$field."`";
									        }

									        $multi_select_values = listing_multi_select_dropdown($table_name, $field_name,$ref_table,$ref_table_col_name,$multi_select_array);

									        ?>
    									<td>
        									<select class="multi_select form-control" name="<?php echo $field; ?>" id="<?php echo $field."_multi_select" ?>" >
        									  <option value="<?php echo $this->config->item('please_select'); ?>"  ><?php echo dashboard_lang("_SELECT_FROM_DROPDOWN_INSIDE_TABLE") ?></option>
        									  <?php


        									  foreach($multi_select_values as $value){

        									      $option_value = @$value[$field];

        									      if($ref_table){
        									          $option_value = $value["name"];
        									      }

        									     if($option_value){


        									  ?>

        									  <option value="<?php echo $option_value;?>"  <?php if($option_value == @$multi_select_array[$field]){echo "selected"; } ?> ><?php echo $option_value; ?></option>

        									  <?php }
        									  }
        									  ?>

        									</select>

    									</td>

    									<?php

        									  }else{
    									    echo "<td></td>";

    									}

									}
									echo "</tr>";
									echo "</form>";

									$fileOrImage = array();
									$dateTooltip = array();
									foreach($all_field as $single_field){
										if($single_field['type'] == "file" || $single_field['type'] == "image"){
											$fileOrImage[] = (string) $single_field['name'];
										}
										if($single_field['type'] == "datetime"){
											$dateTooltip[] = (string) $single_field['name'];
										}

									}

									if (is_array ( $list ) && count ( $list ) > 0) {

										foreach ( $list as $value ) {

											?>
									<tr>
										<td data-href=""><input type="checkbox"
											value="<?php echo $value->{$primary_key}; ?>"
											class="check_uncheck_all" name="check_all" id="check_all" /></td>
										<?php

											foreach ( $listing_field as $field ) {


												?>
										<td width="<?php echo $fieldWidth; ?>%"
											data-href='<?php echo base_url() . $edit_path . "/" . $value->{$primary_key}; ?>'>
											<span data-toggle="tooltip" data-placement="top"
											class="data_listing"
											title="<?php if(! in_array($field, $fileOrImage)){
															if(in_array($field, $dateTooltip)){
															 	echo date("Y-m-d", $value->{$field});
															}else{ echo $value->{$field}; }
														  } ?>"> <?php

												echo dashboard_show_field ( $all_field [$field], $value->{$field} );
												?>
										</span>
										</td>

										<?php } ?>
									</tr>

									<?php
										}
									} else {

										?>
									<tr>
										<td data-href=""
											colspan="<?php echo count($listing_field) + 1; ?>">
											<div class="alert alert-info" role="alert">
												<?php echo dashboard_lang("NO_DATA_FOUND"); ?>
											</div>
										</td>
									</tr>
									<?php
									}

									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!--End table row-->

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dataTables_paginate paging_simple_numbers"
							id="dataTables-example_paginate">
							<?php echo $paging;?>
						</div>
					</div>
				</div>
				<!--End pagination row-->

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php echo $this->load->view($viewPathFooter , array('base_url'=>$base_url ,'site_url'=>$site_url , 'listing_path'=>$listing_path ) ); ?>
					</div>
				</div>
				<!--End legal row-->


			</div>
			<!--End panel-body-->


		</div>
	</div>

</div>
<!--End body -->


<script type="text/javascript">
$(document).ready(function() {

	var pathname = window.location.pathname;

	$("#show_field_add").on("click", function(){

		var checkValues = $('#div_show_field ul li').map(function(){
			return $(this).attr('data-value');
		}).get();

		$('#ajax_load').show();

		$.ajax({
			url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name.'/'.$ajax_update_user_selection; ?>",
			type: 'post',
			data: {fields: checkValues },
			success:function(result){
				$('#ajax_load').hide();
				window.location.href = pathname;
		  }});


		});

	$("#order_field_add").on("click", function(){

					var checkValues = $('#div_order_field ul li').map(function(){
						return $(this).attr('data-value');
					}).get();

					$('#ajax_load').show();

					$.ajax({
						url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name.'/'.$ajax_update_user_order; ?>",
						type: 'post',
						data: {fields: checkValues },
						success:function(result){
							$('#ajax_load').hide();
							window.location.href = pathname;
					  }});


					});

	$("#show_field_reset").on("click", function(){

		$("#div__field ul li").remove();

		var user = userId;

		$('#ajax_load').show();

		$.ajax({
			url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name.'/'.$ajax_reset_user_selection; ?>",
			type: 'post',
			data: {user: user },
			success:function(result){
				$('#ajax_load').hide();
				window.location.href = pathname;
		  }});

	});

	$("#order_field_reset").on("click", function(){

		$("#div_order_field ul li").remove();

		var user = userId;

		$('#ajax_load').show();

		$.ajax({
			url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name.'/'.$ajax_reset_user_order; ?>",
			type: 'post',
			data: {user: user },
			success:function(result){
				$('#ajax_load').hide();
				window.location.href = pathname;
		  }});

	});


	$("#selectall").click(function () {
		var checkAll = $("#selectall").prop('checked');
			if (checkAll) {
				$(".check_uncheck_all").prop("checked", true);
			} else {
				$(".check_uncheck_all").prop("checked", false);
			}
		});

	$("#delete").on("click", function(){

		var checkAllValues = $('.check_uncheck_all:checked').map(function(){
			return $(this).val();
		}).get();

		if(checkAllValues.length == 0){
			alert(selectItemCheckbox);
			return false;
		}else{

			var confirmDelete = confirm(confirmDeleteAlert);

			if (confirmDelete == true) {

				$('#ajax_load').show();

				$.ajax({
					url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name .'/'.$delete_method; ?>",
					type: 'post',
					data: {ids: checkAllValues },
					success:function(result){
						$('#ajax_load').hide();
						window.location.href = pathname;
					}
				 });

			} else {
				return false;
			}

		}

	});

	$("#copy").on("click", function(){

		var checkAllValues = $('.check_uncheck_all:checked').map(function(){
			return $(this).val();
		}).get();

		if(checkAllValues.length == 0){
			alert(selectItemCheckbox);
			return false;
		}else{

			var confirmDelete = confirm(confirmCopyAlert);

			if (confirmDelete == true) {

				$('#ajax_load').show();

				$.ajax({
					url:"<?php echo $base_url."index.php/".$controller_sub_folder.'/'.$class_name .'/'.$copy_method; ?>",
					type: 'post',
					data: {ids: checkAllValues },
					success:function(result){
						$('#ajax_load').hide();
						window.location.href = pathname;
					}
				 });

			} else {
				return false;
			}

		}

	});


	$(document).on("change",".multi_select",function(){

		$("#multi_select_form").submit();

		});

	$(document).on("click","#export_xls",function(){
		$("#export_xls_form").submit();
		});


});
</script>
