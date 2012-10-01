<?php
class Solicitar extends CI_Controller{
    function __construct(){
        parent::__construct();       
        $this->load->model('viagem_ml');
        $this->load->model('usuario_ml');
    }
    
    function index(){
        force_ssl();
        $this->load->view('solicitar_vw');
    }
    
    function criaViagem(){
        force_ssl();
        
        /*Captura o usuario e a sessao*/
        $result = $this->usuario_ml->get_access_token();
        $user = $this->usuario_ml->get_user();
        
        /*Dados do usuario*/
        $usuario['id_usuario'] = $user['facebook_uid'];
        
        /*Verifica se o usuario est� conectado ao facebook*/
        if ($result['is_true']) {
                $this->session->set_userdata(array('access_token' => $result['access_token']));
        } else {
                $this->session->set_userdata(array('access_token' => FALSE));
        }
        
        /*Oferecer carona indica que o soliciatante � o motorista*/
        $params = array(
            'id_usuario' => $user['facebook_uid'],
			'id_motorista' => 0,
            'solicitante' => 1, #padrao solicitar
            'status' => 1
        );
        
        if($this->viagem_ml->setViagem($params)==true){
            redirect (base_url());            
        }else{
            echo "fail";
        }
    }
}  
?>