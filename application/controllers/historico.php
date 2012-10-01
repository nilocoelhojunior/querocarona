<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Historico extends CI_Controller{
    function __construct(){
        parent::__construct();       
        $this->load->model('viagem_ml');
        $this->load->model('usuario_ml');
        $this->load->model('carona_ml');
    }
    function index(){
        /*Captura o usuario e a sessao*/
        $result = $this->usuario_ml->get_access_token();
        $user = $this->usuario_ml->get_user();
        $user_Id = $user['facebook_uid'];
            
            /*Verifica se o usuario est� conectado ao facebook*/
        if ($result['is_true']) {
            $this->session->set_userdata(array('access_token' => $result['access_token']));
        } else {
            $this->session->set_userdata(array('access_token' => FALSE));
          }
          $data = array(
              'ofertadas'=> $this->viagem_ml->mostraViagemAmigos($user_Id, 1),
              'solicitadas'=> $this->viagem_ml->mostraViagemAmigos($user_Id, 0)
              );
          $this->load->view('historico_vw', $data);
    }
}
?>
