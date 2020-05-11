<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script src="<?php echo $base_url;?>dashboardmedia/js/listing.js"></script>
<form name="hidden-form" class="hidden-form-listing"
	action="<?php echo $site_url.$listing_path; ?>" method="get">
	<input type="hidden" name="table_sort_field" value=""
		class="table_sort_field" /> <input type="hidden"
		name="table_sort_direction" value="" class="table_sort_direction">
</form>
<?php
$permissionYesNo = $this->config->item('config_update_permission');
if($permissionYesNo == 'yes'){
	echo show_current_version();
}elseif($permissionYesNo == 'no'){
	if(get_permission_role()){
		echo show_current_version();
	}
}else{
}

?>
