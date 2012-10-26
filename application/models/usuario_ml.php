<?php

class Usuario_ml extends CI_Model {

	function get_user() {
		$query = $this->facebook->getUser();
	
		if ($query) {
			$data['is_true'] = TRUE;
			$data['facebook_uid'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function get_name_user($uid){

		$query = $this->facebook->api($uid);
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $query['name'];
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}

	function get_access_token() {
		$query = $this->facebook->getAccessToken();
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['access_token'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function get_api_secret() {
		$query = $this->facebook->getApiSecret();
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['api_secret'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}

	function get_app_id() {
		$query = $this->facebook->getApiSecret();
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['app_id'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function get_logout_url() {
		
		$query = $this->facebook->getLogoutUrl(array('next' => base_url()));
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['logout_url'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function get_signed_request() {
		$query = $this->facebook->getSignedRequest();
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['signed_request'] = $query;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function get_friends(){
        $query = $this->facebook->api('/me/friends');
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $query;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}

    function set_user($user){
        $test = $this->db->select('id_usuario')->where_in('id_usuario', $user['id_usuario'])->get('tb_usuario')->result();
        if ($test == null){
            $this->db->insert('tb_usuario', $user);               
        }
    }
        
	function set_access_token($access_token) {
		$query = $this->facebook->setAccessToken($access_token);
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	
	function set_api_secret($app_secret) {
		$query = $this->facebook->setApiSecret($app_secret);
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
	function set_app_id($app_id) {
		$query = $this->facebook->setAppId($app_id);
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
        
	function get_full_user() {
        $query = $this->facebook->api('/me');
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $query;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}

	//envia notificacao para usarios do facebook
	function set_notification($recipient_userid){
		
		$data = array(
				'href'=> 'https://apps.facebook.com/querocarona/',
				'access_token'=> '234298743282904|0urKBKqK4ryZIRS-7nWx7NuGEkc',
				'template'=> 'Voc&ecirc; tem novas intera&ccedil;&otilde;es na sua viagem em Quero Carona!'
		);
		$sendnotification = $this->facebook->api('/'.$recipient_userid.'/notifications', 'post', $data);
            //$this->postTimeLine();
		
		if ($sendnotification) {
			$data['is_true'] = TRUE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
	}
        
        //Funcao para postar no mural do autor da viagem
    function postToWall ($params){

            $origem = $params['origem'];
            $destino = $params['destino'];
            $data = $params['data'];
            $horario = $params['hora'];
            $condicao = $params['solicitante'];
            $me = $this->get_full_user();
            $userName = $me['first_name'];
            $id = $params['id_usuario'];

            if ($params['solicitante'] == 1){
            	$message = "Galera, estou em $origem querendo ir pra $destino, por volta das $horario h de $data, algu&eacute;m em ajuda ai com uma carona.";
            }else{
            	$message = "Galera, estou em $origem e indo pra $destino, por volta das $horario h de $data, se alguem estiver afim de uma carona.";
            }

            //variavel que vai receber o array dinâmico
            $wall_post = array (
                //mensagem que simula um post comum
                'message' => $message,
                //nome da aplicação
                'name' => 'Quero Carona!',
                //link para o name acima
                'link' => 'https://facebook.com/querocarona',
                //Slogan
                'caption' => "&Eacute; assim que eu vou.",
                //Espaço reservado para uma descricao da app
                'description' => 'Se voc&ecirc; vai pra algum lugar e quer oferecer ou pedir carona, este &eacute; o lugar.',
                //logotipo da app
                'picture' => 'http://thonnycleuton.com/querocarona/assets/images/logo.png',
                //aqui é onde será postada o link para a viagem que foi criada
                'actions' => array ('name' => 'Deseja ir comigo? Clica aqui','link' => 'https://apps.facebook.com/querocarona'),
                );
            //$message_tags = array('id' => $id,'name' => $userName,'offset' => 0,'type' => 'user','length' => strlen($userName));
            $privacy = array('value' => 'CUSTOM','friends' => 'SELF',);
            $wall_post['privacy'] = json_encode($privacy);
            //$wall_post['message_tags'] = json_encode($message_tags);
            //funcao que chama api para postagem no mural
            $query = $this->facebook->api('/me/feed/', 'post', $wall_post);
            //$this->postTimeLine();
		
		if ($query) {
			$data['is_true'] = TRUE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			return $data;
		}
    }
	
	function get_facebook_object($object, $facebook_uid, $access_token) {
		$fb_connect = curl_init();  
		curl_setopt($fb_connect, CURLOPT_URL, 'https://graph.facebook.com/'.$facebook_uid.'/'.$object.'?access_token='.$access_token);  
		curl_setopt($fb_connect, CURLOPT_RETURNTRANSFER, 1);  
		$output = curl_exec($fb_connect);  
		curl_close($fb_connect);  
		
		$result = json_decode($output);
		
		if (isset($result->error)) {
			$data['is_true'] = FALSE;
			$data['message'] = $result->error->message;
			$data['type'] = $result->error->type;
			$data['code'] = $result->error->code;
		
			return $data;
		} else {
			$data['is_true'] = TRUE;
			$data['data'] = $result->data;
			
			return $data;
		}
	}
	
	//ainda em testes, nenhuma feature utiliza esta fun�ao **Thonnycleuton
	function postTimeLine(){
        $token = $this->get_access_token();
        $params = array(
            'access_token' => $token['access_token'],
            '{object}' => 'http://samples.ogp.me/393060727406704',
        );
        try{
            $result = $this->facebook->api('/me/querocarona:ride', 'POST', $params);
            echo $result;
        }
        catch(FacebookApiException $e){
            echo $e;
        }
   }
}