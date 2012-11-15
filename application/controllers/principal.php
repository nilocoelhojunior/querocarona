<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {
	
	function __construct(){
		parent::__construct();

		error_reporting(0);
		ini_set(ìdisplay_errorsî, 0 );
	}

	function index($id_viagem) {	
		$this->home($id_viagem);
	}

	function home($id_viagem){
		$result = $this->usuario_ml->get_user();

		if ($result['is_true']) {

            $me = $this->usuario_ml->get_full_user();          

            $this->session->set_userdata(array('facebook_uid' => $result['facebook_uid'], 'name_uid'=> $me['name'], 'is_logged_in' => TRUE));

            $this->auth->is_logged_in();

            $usuario = array('id_usuario' => $this->session->userdata('facebook_uid'), $this->session->userdata('name_uid'));

			$this->usuario_ml->set_user($usuario);

			/* Atualiza status da viagem */
			$this->viagem_ml->atualizaViagens();

			$dados = array('viagem'=>$id_viagem);
			
			/*Carrega a view principal*/
			$this->load->view('principal_vw', $dados);

        } else {
            $string = $this->facebook->getLoginURL($this->config->item('facebook_login_parameters'));
            redirect($string);
        }
	}

    //Recebe os dados do formulario e cria uma nova viagem
    function criaviagem($tipo){

		if($tipo == 'chk_ofertar'){
			$info_tipo = 0;
			$mensagem = "Carona criada com sucesso";
            $tipo_msg = 'ofertou';
		}else if($tipo == 'chk_solicitar'){
			$info_tipo = 1;
			$mensagem = "Carona solicitada com sucesso";
            $tipo_msg = 'solicitou';
		}

		if ($tipo == 'chk_ofertar' || $tipo == 'chk_solicitar'){
			$params = array(
			    'id_usuario' => $this->session->userdata('facebook_uid'),
			    'nome' => $this->session->userdata('name_uid'),
			    'solicitante' => $info_tipo,
			    'status' => 1, 
			    'origem' => $this->input->post('origem'),
			    'destino' => $this->input->post('destino'),  
			    'data' => $this->input->post('data'),
			    'hora' => $this->input->post('hora'),
			    'obs' => $this->input->post('obs')
			);

			$resultado = "Verifique os dados e envie novamente";
			$info = 2;
			if ($params['destino'] != '' && $params['origem'] != '' && $params['data'] && $params['hora']){
				
				$post = $this->viagem_ml->setViagem($params);

				if($post){
				    //aqui chama a funcao para postar no mural, somente apos a viagem ser criada
					$params['id_viagem'] = $post;
					
				    $result = $this->usuario_ml->postToWall($params);
				    $resultado = $mensagem;
				    $info = 1;
				}
			}else{
	            $resultado = "Verifique os dados e envie novamente";
	            $info = 2;
			}
		}else {
			$resultado = "Verifique os dados e envie novamente";
			$info = 2;
		}	

		$teste = array('tipo' => $info, 'viagem' => $resultado);
		$resposta = json_encode($teste);
		echo $resposta;
    }

    //Retorna um json com viagens suas ou dos seus amigos, dependendo do tipo recebido
    function exibeviagem($tipo){
		
		if ($tipo == 1){
			$amigos = $this->usuario_ml->get_friends();
		
			/*Captura os amigos do usuario*/
			for ($i=0; $i < count($amigos['data']); $i++){
				$friends [$i] = $amigos['data'][$i]['id'];
			}

			$data = $this->viagem_ml->viagensDeMeusAmigos($friends, 1);
			$montaviagem = "Seus amigos n„o possuem viagens";
		}else if($tipo == 2){			
			$data = $this->viagem_ml->minhasViagens($this->session->userdata['facebook_uid']);
			$montaviagem = "VocÍ n„o possui nenhuma viagem crie j· a sua!";
		}
		
		if ($data == null){
			$resultado = array("tipo" => 1, "viagem" => utf8_encode($montaviagem));
			$resposta = json_encode($resultado);
			echo $resposta;
			
		}else{
			$montaviagem = new StdClass;
			foreach ($data as $key=>$value) {
				$data = date("d/m/Y", strtotime($value->data));
				$hora = date("H:i", strtotime($value->hora));

				if($value->solicitante == 0){
					$tipo = 'ofertou';
				}else if($value->solicitante == 1){
					$tipo = 'solicitou';
				}
				$montaviagem->$key->nome = $value->nome;
				$montaviagem->$key->tipo = $tipo;
				$montaviagem->$key->origem = $value->origem;
				$montaviagem->$key->destino = $value->destino;
				$montaviagem->$key->data = $data;
				$montaviagem->$key->hora = $hora;
				$montaviagem->$key->id_usuario = $value->id_usuario;
				$montaviagem->$key->id_viagem = $value->id_viagem;
				$montaviagem->$key->status = $value->status;
			}
		$resultado = array("tipo" => 2, "viagem" => $montaviagem);
		$resposta = json_encode($resultado);
		echo $resposta;    	
		}
    }

    //Retorna um json da viagem recebida detalhando-a com os passageiros confirmados
    //e/ou solicitados, se voce for dono da viagem voce poder· ver os solicitados
    function exibecarona($id_viagem){

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);
		$data = date("d/m/Y", strtotime($buscaviagem[0]->data));

		$buscaviagem[0]->data = $data;

		if($buscaviagem[0]->solicitante == 0){
			$tipo_solicitacao = 'Ofertou';
		}else if($buscaviagem[0]->solicitante == 1){
			$tipo_solicitacao = 'Solicitou';
		}

		$buscaviagem[0]->tipo_solicitacao = $tipo_solicitacao;

		//Testa se o usuario √© o criado da viagem
		if ($this->session->userdata('facebook_uid') == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$tipo = 1;

			if ($buscaviagem[0]->status == 0){
				$botao2 = ''				;
			}else if ($buscaviagem[0]->status == 1){
				$botao2 = '<button id="excluirviagem" class="btn" onclick="excluirviagem('.$buscaviagem[0]->id_viagem.')">Excluir</button><button id="efetuarcarona" class="btn btn-primary" onclick="efetuarcarona('.$buscaviagem[0]->id_viagem.')">Efetuar Carona</button>';
			}

			$buscacarona = $this->carona_ml->buscacarona($id_viagem, $tipo);

		}else{
			//Usuario n√£o √© o criador da viagem
			$tipo = 2;
			$botao2 = '<button id="solicitarcarona" class="btn btn-success" onclick="solicitarcarona('.$buscaviagem[0]->id_viagem.')">Solicitar Carona</button>';

			$buscacarona = $this->carona_ml->buscacarona($id_viagem, $tipo);		
		}

		$resultado = array(
				'viagem' => $buscaviagem,
				'tipo' => $tipo, 
				'botao2' => $botao2, 
				'carona' => $buscacarona);
		
		$resposta = json_encode($resultado);
		echo $resposta;
    }

    //Recebe um $id_viagem de um amigo e o usuario vai solicitar uma carona
	function solicitar_carona($id_viagem){

		$criador_viagem = $this->viagem_ml->buscaviagem($id_viagem);		

		$busca_usuario_na_carona = $this->carona_ml->busca_usuario_na_carona($id_viagem, $this->session->userdata('facebook_uid'));

		if ($this->session->userdata('facebook_uid') == $criador_viagem[0]->id_usuario){
		    
			$resultado = 'VocÍ j· est· participando dessa carona';
			$info = 2;

		}else if($busca_usuario_na_carona != null){

			$resultado = 'Por favor aguarde a confirmacao da sua carona';
			$info = 2;

		}else if($busca_usuario_na_carona == null){

			$dados = array(
				'id_viagem' => $id_viagem,
				'id_usuario' => $this->session->userdata('facebook_uid'),
				'nome' => $this->session->userdata('name_uid')
			);

			$tipo = 0;

			$result = $this->carona_ml->insereusuario($dados, $tipo);

			if ($result == 2){
		    	$resultado = 'Ops! Tente novamente';
		    	$info = 2;
		    }else if($result == 1){
		    	$resultado = 'SolicitaÁ„o enviada com sucesso';
				$message = '@['.$this->session->userdata('facebook_uid').'] quer ir na carona que voce criou';

		    	//envia notificaÁ„o para o usuario
		    	$this->usuario_ml->set_notification($criador_viagem[0]->id_usuario, $message, $criador_viagem[0]->id_viagem);

		    	$info = 1;
		    }
		}

		$result = array("tipo" => $info, "viagem" => utf8_encode($resultado));
		$resposta = json_encode($result);
        echo $resposta;
	}
    
    //Usuario exclui uma viagem sua
	function excluir_viagem($id_viagem){

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($this->session->userdata('facebook_uid') == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$result = $this->viagem_ml->excluirviagem($id_viagem);

			if ($result == true){
				$resultado = 'Viagem excluida com sucesso';
				$info = 1;
			}else{
				$resultado = 'Ops! Tente novamente';
				$info = 2;
			}
		}else{
			$resultado = 'VocÍ n„o pode fazer isso';
			$info = 2;
		}

		$mensagem = array("tipo" => $info, "viagem" => utf8_encode($resultado));
		$resposta = json_encode($mensagem);
        echo $resposta;
	}
    
    //Usuario seleciona um amigo $id_usuario e insere ele na sua viagem $id_viagem
    function inserir_usuario_na_carona($id_viagem, $id_usuario){

    	$me = $this->usuario_ml->get_name_user($id_usuario);

		$dados['id_usuario'] = $id_usuario;
		$dados['nome'] = $me;
		$dados['id_viagem'] = $id_viagem;

		$tipo = 1;

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($this->session->userdata('facebook_uid') == $buscaviagem[0]->id_usuario){

			$result = $this->carona_ml->insereusuario($dados, $tipo);

			if ($result == 1){
				$resultado = '<b>'.$dados['nome'].'</b> inserido(a) com sucesso';

				//envia notificaÁ„o para o usuario
				$message = 'Parabens, sua solicitacao de carona foi aceita!';
				$message_notifications = '@['.$this->session->userdata('facebook_uid').'] confirmou voce na carona!';
				$this->usuario_ml->set_notification($id_usuario, $message_notifications, $id_viagem);

				$info = 1;
			}else{
				$resultado = 'Ops! Tente novamente';
				$info = 2;
			}
        }else{
        	$resultado = 'VocÍ n„o pode fazer isso';
        	$info = 2;
        }
		
		$mensagem = array("tipo" => $info, "viagem" => utf8_encode($resultado));
		$resposta = json_encode($mensagem);
        echo $resposta;
    }

    //Usuario seleciona um amigo $id_usuario e exclui ele da sua viagem $id_viagem
    function remover_usuario_da_carona($id_viagem, $id_usuario){

		$me = $this->usuario_ml->get_name_user($id_usuario);

		$dados['id_usuario'] = $id_usuario;
		$dados['nome'] = $me;
		$dados['id_viagem'] = $id_viagem;

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		$busca_usuario_na_carona = $this->carona_ml->busca_usuario_na_carona($id_viagem, $this->session->userdata('facebook_uid'));

		if ($this->session->userdata('facebook_uid') == $buscaviagem[0]->id_usuario){

			$result = $this->carona_ml->excluirusuario($dados);

			if ($result == 1){
				$resultado = '<b>'.$dados['nome'].'</b> removido(a) com sucesso';

				//envia notificaÁ„o para o usuario
				$message_not = '@['.$this->session->userdata('facebook_uid').'] removeu voce da carona. Veja com seu amigo o que aconteceu';
				$this->usuario_ml->set_notification($id_usuario, $message_not, $id_viagem);

				$info = 1;
			}else{
				$resultado = 'Ops! Tente novamente';
				$info = 2;
			}
        }else if($busca_usuario_na_carona != null){
        	$resultado = 'VocÍ n„o pode fazer isso';

        	$result = $this->carona_ml->excluirusuario($dados);

			if ($result == 1){
				$resultado = 'Voce foi removido(a) da carona';

				//envia notificaÁ„o para o usuario
				$message_not = '@['.$this->session->userdata('facebook_uid').'] saiu da carona. Veja com seu amigo o que aconteceu';
				$this->usuario_ml->set_notification($buscaviagem[0]->id_usuario, $message_not, $id_viagem);

				$info = 1;
			}else{
				$resultado = 'Ops! Tente novamente';
				$info = 2;
			}$info = 1;
        }else{
        	$resultado = 'VocÍ n„o pode fazer isso';
        	$info = 2;
        }

		$mensagem = array("tipo" => $info, "viagem" => utf8_encode($resultado));
		$resposta = json_encode($mensagem);
        echo $resposta;
    }

    //Usuario realiza sua viagem $id_viagem antes do data prevista
    function fecharviagem($id_viagem){

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($this->session->userdata('facebook_uid') == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$result = $this->viagem_ml->fecharviagem($id_viagem);

			if ($result == true){
				$resultado = 'Passageiros abordo. Boa viagem!';
				$info = 1;
			}else{
				$resultado = 'Ops! Tente novamente';
				$info = 2;
			}
		}else{
			$resultado = 'VocÍ n„o pode fazer isso';
			$info = 2;
		}

		$mensagem = array("tipo" => $info, "viagem" => utf8_encode($resultado));
		$resposta = json_encode($mensagem);
        echo $resposta;
    }

    //Pesquisa din‚mica
    function buscadinamica(){
    	$palavra = $this->input->get('name_startsWith');
    	$tipo = $this->input->get('tipo');
    	
    	//viagens amigos do usuario
	    if($tipo == 1){
			$amigos = $this->usuario_ml->get_friends();
		
			/*Captura os amigos do usuario*/
			for ($i=0; $i < count($amigos['data']); $i++){
				$friends [$i] = $amigos['data'][$i]['id'];
			}

			$busca = $this->viagem_ml->buscadinamica($palavra, $friends);

	    }else /*viagens usuario*/ if($tipo == 2){
	    	$busca = $this->viagem_ml->buscadinamica($palavra, $this->session->userdata('facebook_uid'));
	    }

		$montaviagem = new StdClass;
			foreach ($busca as $key=>$value) {
				$data = date("d/m/Y", strtotime($value->data));
				$hora = date("H:i", strtotime($value->hora));

				if($value->solicitante == 0){
					$tipo = 'ofertou';
				}else if($value->solicitante == 1){
					$tipo = 'solicitou';
				}
				$montaviagem->$key->nome = $value->nome;
				$montaviagem->$key->tipo = $tipo;
				$montaviagem->$key->origem = $value->origem;
				$montaviagem->$key->destino = $value->destino;
				$montaviagem->$key->data = $data;
				$montaviagem->$key->hora = $hora;
				$montaviagem->$key->id_usuario = $value->id_usuario;
				$montaviagem->$key->id_viagem = $value->id_viagem;
				$montaviagem->$key->status = $value->status;
			}

    	$resposta = json_encode($montaviagem);
    	echo $resposta;
    }

}