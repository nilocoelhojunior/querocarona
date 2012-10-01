<?php
class Ofertar extends CI_Controller{
    function __construct(){
        parent::__construct();       
        $this->auth->is_logged_in();
    }
    
    function index(){
        force_ssl();

        $user = $this->usuario_ml->get_user();

        /*Verifica se o usuario esta conectado ao facebook*/
        if ($user['is_true']) {

            $this->session->set_userdata(array('facebook_uid' => $user['facebook_uid'], 'is_logged_in' => TRUE));

            $usuario['id_usuario'] = $user['facebook_uid'];

            /*Oferecer carona indica que o soliciatante � o motorista*/
            $params = array(
                'id_usuario' => $usuario['id_usuario'],
                'id_motorista' => $usuario['id_usuario'],
                'solicitante' => 0, #padrao ofertar
                'status' => 1, 
                'origem' => $this->input->post('origem'),
                'destino' => $this->input->post('destino'),  
                'data' => $this->input->post('data'),
                'hora' => $this->input->post('hora'),
                'obs' => $this->input->post('obs')
            );
            
            if($this->viagem_ml->setViagem($params)==true){
                //aqui chama a funcao para postar no mural, somente apos a viagem ser criada
                $rsult = $this->usuario_ml->postToWall($params);
                #echo "oi";
                $resposta = array('status' => 0);
                echo json_encode($resposta);
            }else{
                #echo "false";
                $resposta = array("status" => 1);
                echo json_encode($resposta);
            }
        }else{
            $resposta = array("status" => 2);
            echo json_encode($resposta);
        }
    }
}  
?>