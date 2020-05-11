<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="button_row">
	<?php if(get_permission_role()):?>
	<a href="<?php  echo base_url().$edit_path; ?>" class="btn_add">
		<button type="button" class="btn btn-primary">
			<?php echo dashboard_lang('_DASHBOARD_LISTING_ADD'); ?>
		</button>
	</a>
	<button type="button" name="copy" id="copy" class="btn btn-primary">
		<?php echo dashboard_lang('_DASHBOARD_LISTING_COPY'); ?>
	</button>	
	<button type="button" name="delete" id="delete" class="btn btn-danger">
		<?php echo dashboard_lang('_DASHBOARD_LISTING_DELETE'); ?>
	</button>
	<form method="post" id="export_xls_form" action="<?php echo base_url()."dbtables/".$class_name."/export/"; ?>" >
	<button type="button" name="export_xls" id="export_xls" class="btn btn btn-primary">
		<?php echo dashboard_lang('EXPORT'); ?>
	</button>
	</form>
	<?php endif; ?>	
</div>
