<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Check whether the site is offline or not.
 *
 */
class Auth_hook {
	
	private $CI;

    function Auth_hook()
    {
        $this->CI =& get_instance();

        if(!isset($this->CI->session)){  //Check if session lib is loaded or not
			$this->CI->load->library('session');  //If not loaded, then load it here
        }
    }
    
    function check(){
		if(file_exists(APPPATH.'config/config.php')){
            include(APPPATH.'config/config.php');
            
			//echo "--->".$this->CI->session->userdata('logged_in');
			if($this->CI->session->userdata('logged_in'))
				$sistema = $this->CI->session->userdata('sistema');
			else
				$sistema = $config['sistema'];
			
			//echo $sistema; die;
			
			//echo "--->".$config['sistema']."--->".$sistema."<---"; die;
			
            if($config['sistema'] != $sistema){ //echo "--->".$config['sistema']."--->".$sistema."<---"; die;
                include(APPPATH.'controllers/admin/admin.php');
				$this->CI->session->sess_destroy();
				redirect('admin/adm/login');
                exit;
            }
        }
    }

}