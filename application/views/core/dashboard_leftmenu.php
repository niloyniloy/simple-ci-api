<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' ); ?>
<div class="sidebar-nav navbar-collapse">
	<ul class="nav" id="side-menu">
<?php 	$menu_items =  dashboard_get_menu_left(); 
		$output_html='';
		if( count($menu_items) ){
			
			foreach( $menu_items as $menu ){
				
                if ( $menu->display_text != 'users' ) {

					$output_html .='<li>';
					$output_html .='<a class="'.$menu->class.'" ';
					$output_html .=' href="'.$menu->href.'" >';
					$output_html .= $menu->display_text ;
					$output_html .='</a>';
					$output_html .='</li>';

			  }
				
			}
			
		}

		    $user_id = $this->session->userdata('user_id');

		    if ( $user_id == '1' ) {
				$output_html .='<li>';
				$output_html .='<a class="menu-anchor" ';
				$output_html .=' href="'.base_url('dbtables/users/listing').'" >';
				$output_html .=  "users" ;
				$output_html .='</a>';
				$output_html .='</li>';
			}

		    echo $output_html;


?>
	</ul>
</div>
