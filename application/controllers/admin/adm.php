<?php
class Adm extends CI_Controller {
		
	public function Adm() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		//$this->output->enable_profiler(TRUE);
	}
	function index() {
		if($this->session->userdata('logged_in')) {
			redirect('admin/administradores');
		} else {
			redirect('admin/adm/login');
		}  


		$this->load->model('distrito_m');
        $data['estados'] = $this->distrito_m->getEstados();
        
        $this->load->view('datos_cuenta', $data);
      
	}
	function login() {
		//$this->load->library ( 'form_validation' );
		
		$this->load->helper( 'security' );
		if (! isset ( $_POST ['username'] )) {
			$this->load->view ( 'backend/login' );
		} else {
			//$this->form_validation->set_rules ( 'maillogin', 'e-mail', 'required|valid_email' ); 
			$this->form_validation->set_rules ( 'username', 'Usuario', 'required' ); 
			$this->form_validation->set_rules ( 'password', 'Constraseña', 'required' );
			if (($this->form_validation->run () == FALSE)) {
				$this->load->view ( 'backend/login' ); /* 1ra validación */
			} else {
				$this->load->model ( 'administradores_m' );
				$es_usuario_valido = $this->administradores_m->validar_usuario ( $_POST ['username'], $_POST ['password'] );
				if ($es_usuario_valido) {
					
					//echo "Validacion Ok<br><br><a href=''>Volver</a>";
					$this->session->set_userdata("usuario_id",$es_usuario_valido->id);
					$this->session->set_userdata("usuario_perfil",$es_usuario_valido->perfil);
					$this->session->set_userdata("usuario_username",$es_usuario_valido->username);
					$this->session->set_userdata("logged_in",TRUE);
					$this->session->set_userdata("sistema", "estadodiario");
					$this->session->set_userdata("usuario_perfil",$es_usuario_valido->perfil);
					if( $this->session->userdata("usuario_perfil") != 3 ){
						redirect('admin/procurador/');
					}else{
						redirect('admin/procurador/');
					}
					
					
				} else {
					$data ['error'] = "Usuario o contraseña incorrecta, por favor vuelva a intentar";
					$this->load->view ( 'backend/adm/login', $data );
				}
			}
		}
	}
	function logout(){
		$this->session->sess_destroy();
		redirect('admin/adm/login');
	}
}

?>