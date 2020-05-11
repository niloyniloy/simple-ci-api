<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/core/Dashboard_controller.php";

class Plan_types extends Dashboard_controller {	

	public $current_class_name;		

	function __construct()	{		
		parent::__construct();				
		$this->current_class_name = strtolower( __CLASS__ ) ;	
	}	

	public function index()	{		

	}				

}
