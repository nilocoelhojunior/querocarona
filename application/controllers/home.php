<?php
 
class Home extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    function index(){        
        $result = $this->usuario_ml->get_user();
        
        if ($result['is_true']) {
                $this->session->set_userdata(array('facebook_uid' => $result['facebook_uid'], 'is_logged_in' => TRUE));
                redirect('principal', 'refresh');
        } else {
            $string = $this->facebook->getLoginURL($this->config->item('facebook_login_parameters'));
            redirect($string);
        }
    } 
}  
?>
