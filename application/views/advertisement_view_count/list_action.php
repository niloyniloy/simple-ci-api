<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="button_row">
	<?php if(get_permission_role()):?>
	<form method="post" id="export_xls_form" action="<?php echo base_url()."dbtables/".$class_name."/export/"; ?>" >
	<button type="button" name="export_xls" id="export_xls" class="btn btn btn-primary">
		<?php echo dashboard_lang('EXPORT'); ?>
	</button>
	</form>
	<?php endif; ?>	
</div>
