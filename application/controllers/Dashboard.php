<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	public function __construct(){
		parent::__construct();		
		$this->load->library('template');
		$this->load->library('session');
		$this->config->load('dashboard');
		$this->config->load('dashboard_override', FALSE, TRUE);
		$this->load->helper('dashboard_main');
		$this->load->helper('dashboard_menu');
		// template
		$this->template->set_template( $this->config->item('template_name') );
		
		$this->lang->load('dashboard');
		$this->lang->load('dashboard_override');
	}
	
	public function index(){
		
		$redirect_path = $this->config->item('redirect_path_after_login');
		
		if($this->check_user_loggedin()){
			
			redirect(base_url($redirect_path));
			
		}else{
			
			$post = $this->input->post();			
			if($post && $post['email']){
				$loginTrue = $this->login($post['email'],$post['password']);
				if($loginTrue){				
					redirect(base_url($redirect_path));
				}
			}
			
			$data = array();
			$data['login'] = "Login in";
			$this->load->view('upgrade/login', $data);			
			
		}

	}
	
	public function home(){	
		
		$redirect_path = $this->config->item('login_url');
		
		if( $this->check_user_loggedin() ){
		
			$data = array();
			$data['message'] = dashboard_lang('_DASHBOARD_HOME_MESSAGE');
			$this->template->write_view('content', 'core/homepage', $data);
			$this->template->render();
			
		}else{		
			
			redirect(base_url().$redirect_path);
						
		}
	}
	
	public function reset_password(){
	    $this->load->helper('dashboard_list');
	    $post = $this->input->post();
	    
	    if($post){
	        $reset_email = $post['reset_email'];
	        
	        $this->db->select('*');
	        $result = $this->db->get_where('dashboard_login',array('email'=>$reset_email),1);
	        $data_result = $result->row_array();
	        
	        if($data_result){
	          
	            $token = md5(time());
	            $from = $this->config->item('reset_pass_admin_email');
	            $to = $reset_email;
	            $subject = dashboard_lang("_RESET_PASS_SUBJECT");
	            // this load the sub view file for email body msg
	            $view_file = "core/email_body_template" ;
	            $data['message'] = dashboard_lang('_RESET_PASS_MAIL_INTRO').
    	   	        "<a target='_blank' href='".base_url()."dashboard/input_reset_password?token=".$token."'>".dashboard_lang("_PASSWORD_RESET")."</a>";
	            
	            send_mail($from, $to, $subject, $view_file, $data);
    	        
    	        $where = array('email'=>$reset_email);
    	        $this->db->select('*');
    	        $result = $this->db->get_where('reset_password',$where,1);
    	        $row = $result->row_array();
    	        
    	        if($row){
    	        $this->db->where('id',$row['id']);    
    	        $this->db->update("reset_password",array('token'=>$token));
    	        }else{
    	        
    	        $ins = array('user_id'=>$data_result['id'],'email'=>$reset_email,'token'=>$token);
    	        $this->db->insert("reset_password",$ins);
    	        }
    	        
    	        echo dashboard_lang('_RESET_PASS_EMAIL_CONFIRM');
    	        
    	        //echo $this->email->print_debugger();
	        
	        }else{
	            echo dashboard_lang('_EMAIL_NOT_EXISTS');
	        }
	        
	    }
	    
	    
	}
	
	
	public function input_reset_password(){
	    
	   $post = $this->input->post();
	   $token = $this->input->get('token');
	   $this->db->select('*');
	   $result = $this->db->get_where('reset_password',array('token'=>$token),1);
	   $row = $result->row_array();
	   $data = array();
	   if($post){
	       $new_pass = md5($post['new_password']);
	       
	       if($post['new_password'] == $post['re_password']){
	       
	       $this->db->select('*');
	       $result = $this->db->get_where('dashboard_login',array('email'=>$row['email']),1);
	       $data_result = $result->row_array();
	       if($data_result){
	           // update pass
	           $this->db->where('id',$data_result['id']);
	           $update1 = $this->db->update("dashboard_login",array('password'=>$new_pass));
	           
	           // update token
	           $this->db->where('id',$row['id']);
	           $update2 = $this->db->update("reset_password",array('token'=>""));
	           
	           if($update1 && $update2){
	              
	               
	               redirect(base_url()."dashboard");
	               
	           }
	       }else{
	           echo dashboard_lang('_EMAIL_NOT_EXISTS');
	       }
	      }else{
	          echo dashboard_lang('_RE_PASS_NOT_MATCH');
	      }  
	       
	   }else{
	       
	       
	       if($row){
	           $this->load->view('core/reset_password', $data);
	           
	       
	       }
	   } 
	   
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("http://communiverseintl.com/");
	}
	
	private function check_user_loggedin(){
		
		$user_id_config = $this->config->item('user_id');
		//Check if already logged in
		if($this->session->userdata($user_id_config)){
			
			return true;
			
		}else{
			
			return false; 
		}
		
	}
	
	function login($user_email = '', $user_pass = '')
	{	
	
		$user_id_config = $this->config->item('user_id');
		//Check if already logged in
		if($this->session->userdata($user_id_config))
			return true;
	
	
		//Check against user table
		$user_pass = md5($user_pass);
	
		$this->db->where(array('email'=>$user_email,'password'=>$user_pass,'status'=>1));
		$query = $this->db->get_where("dashboard_login");	
	
	
		if ($query->num_rows() > 0)
		{
			$userData = $query->row_array();
			
			$user_id_config = $this->config->item('user_id');
			$user_role = $this->config->item('user_role');
			$this->session->set_userdata($user_id_config, $userData['id']);
			$this->session->set_userdata($user_role, $userData['role']);
				
			return true;
		}
		else
		{
			$this->session->set_flashdata('login_error', dashboard_lang('DASHBOARD_LOGIN_ERROR_MESSAGE'));
			return false;
		}
	
	}

	public function change_password(){
		
		$post = $this->input->post();
		
		if(isset($post) && $post['current_password']){
			
			$currentPassword = $post['current_password'];
			$newPassword = $post['new_password'];
			
			$user_current_pass = md5($currentPassword);			
			$user_new_pass = md5($newPassword);
			
			$this->db->where(array('id'=>get_user_id(), 'password' => $user_current_pass));
			$query = $this->db->get_where("dashboard_login");
			
			if($query->num_rows() > 0){
				
				$data = array(
						'password' => $user_new_pass,
				);
				
				$tableName = 'dashboard_login';
				
				$this->db->where("id", get_user_id());
				$this->db->update($tableName, $data);
				
				$this->session->set_flashdata('change_password_message', dashboard_lang('PASSWORD_CHANGED_SUCCESS'));
				
			}else{
				$this->session->set_flashdata('change_password_error', dashboard_lang('CURRENT_PASSWORD_NOT_MATCH'));
			}
			
		}
		
		if(! get_user_id()){
			redirect(site_url($this->config->item('login_url')));
		}
		
		$this->db->where(array('id'=>get_user_id()));
		$query = $this->db->get_where("dashboard_login");	
		
		$data['data'] = $query->row_array();
		
		$this->template->write_view('content', 'core/dashboard/setting', $data);
		$this->template->render();
				
		
	}
	
public function signup() {
    
    $post = $this->input->post();
    if($post){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[dashboard_login.email]');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view("core/signup");
        }
        else
        {
            
            $ins = array(
                'name'=>$post['name'],
                'email'=>$post['email'],
                'password'=>md5($post['password']),
                'role'=>"user", // default user
                'status'=>0, // default inactive
            );
            
            $this->db->insert("dashboard_login",$ins);
            redirect( base_url()."dashboard");
        }
        
        
        
    }else{
        $this->load->view("core/signup");
    }
    
    
}	
	
}