<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {

	function __construct() {
		parent::__construct(); 
		$this->auth->is_logged_in();
	}

	function index() {

		$user = $this->usuario_ml->get_user();                
		
		$usuario['id_usuario'] = $user['facebook_uid'];
		$me = $this->usuario_ml->get_full_user();
		$usuario['nome'] = $me['name'];
		$this->usuario_ml->set_user($usuario);

		/*Atualiza status da viagem*/
		$this->viagem_ml->atualizaViagens();

		/*Carrega a view principal*/
		$this->load->view('principal_vw');
	}
	
	//Exibe as notificações para o usuário
	function notificacoes(){
		$resultado = array("viagem" => "lol");
		$resposta = json_encode($resultado);
		echo $resposta;
	}

    //Recebe os dados do formulario e cria uma nova viagem
    function criaviagem($tipo){

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];
		$me = $this->usuario_ml->get_full_user();
		$usuario['nome'] = $me['name'];

		if($tipo == 'chk_ofertar'){
			$info_tipo = 0;
			$mensagem = '<div id="info" class="info_sucesso">
		    				<span>Carona criada com sucesso</span>
                    	</div>';
            $tipo_msg = 'ofertou';
		}else if($tipo == 'chk_solicitar'){
			$info_tipo = 1;
			$mensagem = '<div id="info" class="info_sucesso">
		    				<span>Carona solicitada com sucesso</span>
                    	</div>';
            $tipo_msg = 'solicitou';
		}

		if ($tipo == 'chk_ofertar' || $tipo == 'chk_solicitar'){
			$params = array(
			    'id_usuario' => $usuario['id_usuario'],
			    'nome' => $usuario['nome'],
			    'solicitante' => $info_tipo,
			    'status' => 1, 
			    'origem' => $this->input->post('origem'),
			    'destino' => $this->input->post('destino'),  
			    'data' => $this->input->post('data'),
			    'hora' => $this->input->post('hora'),
			    'obs' => $this->input->post('obs')
			);

			if($this->viagem_ml->setViagem($params)==true){
			    //aqui chama a funcao para postar no mural, somente apos a viagem ser criada

			    $result = $this->usuario_ml->postToWall($params);
			    $resultado = $mensagem;
			}else{
	            $resultado = '<div id="info" class="info_error">
			    				                  	<span>Verifique os dados e envie novamente</span>
	                    						  </div>';
			}
		}else {
			$resultado = '<div id="info" class="info_error">
			    				                  	<span>Verifique os dados e envie novamente</span>
	                    						  </div>';
		}	
		echo $resultado;
    }

    //Retorna um json com viagens suas ou dos seus amigos, dependendo do tipo recebido
    function exibeviagem($tipo){

    	$result = $this->usuario_ml->get_access_token();

		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];
		$me = $this->usuario_ml->get_full_user();
		$usuario['nome'] = $me['name'];

		$amigos = $this->usuario_ml->get_friends();

		/*Captura os amigos do usuario*/
		for ($i=0; $i<count($amigos['data']); $i++){
			$friends [$i] = $amigos['data'][$i]['id'];
		}

		if ($tipo == 1){
			$data = $this->viagem_ml->amigosViagens($friends, 1);
			$montaviagem = "Seus amigos não possuem viagens";
		}else if($tipo == 2){
			$data = $this->viagem_ml->minhasViagens($usuario['id_usuario']);

			$montaviagem = "Você não possui nenhuma viagem crie já a sua";
		}
		
		
		if ($data == null){
			$resultado = array("tipo" => 1, "viagem" => $montaviagem);
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
			}
		$resultado = array("tipo" => 2, "viagem" => $montaviagem);
		$resposta = json_encode($resultado);
		echo $resposta;    	
		}
    }

    //Retorna um json da viagem recebida detalhando-a com os passageiros confirmados
    //e/ou solicitados, se voce for dono da viagem voce poderá ver os solicitados
    function exibecarona($id_viagem){

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if($buscaviagem[0]->solicitante == 0){
			$tipo_solicitacao = 'Ofertou';
		}else if($buscaviagem[0]->solicitante == 1){
			$tipo_solicitacao = 'Solicitou';
		}

		$buscaviagem[0]->tipo_solicitacao = $tipo_solicitacao;

		//Testa se o usuario é o criado da viagem
		if ($usuario['id_usuario'] == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$tipo = 1;

			if ($buscaviagem[0]->status == 0){
				$botao2 = ''				;
			}else if ($buscaviagem[0]->status == 1){
				$botao2 = '<button id="excluirviagem" class="btn" onclick="excluirviagem('.$buscaviagem[0]->id_viagem.')">Excluir</button><button class="btn btn-primary" onclick="efetuarcarona('.$buscaviagem[0]->id_viagem.')">Efetuar Carona</button>';
			}

		}else{
			//Usuario não é o criador da viagem
			$tipo = 2;
			$botao2 = '<button class="btn btn-success" onclick="solicitarcarona('.$buscaviagem[0]->id_viagem.')">Solicitar Carona</button>';
		}

		$buscacarona = $this->carona_ml->buscacarona($id_viagem, $tipo);

		$resultado = array('viagem' => $buscaviagem, 'tipo' => $tipo, 'botao2' => $botao2, 'carona' => $buscacarona);
		$resposta = json_encode($resultado);
		echo $resposta;
    }

	function solicitar_carona($id_viagem){

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];
		$me = $this->usuario_ml->get_full_user();
		$usuario['nome'] = $me['name'];

		$criador_viagem = $this->viagem_ml->buscaviagem($id_viagem);		

		$busca_usuario_na_carona = $this->carona_ml->busca_usuario_na_carona($id_viagem, $usuario['id_usuario']);

		if ($usuario == $criador_viagem[0]->id_usuario){
		    
			$resultado = '<div id="info" class="info_error"><span>Você já está participando dessa carona</span></div>';
			$resposta = json_encode($resultado);
            echo $resposta;

		}else if($busca_usuario_na_carona != null){
			$resultado = '<div id="info" class="info_error"><span>Você já está participando dessa carona</span></div>';
			$resposta = json_encode($resultado);
            echo $resposta;
		}else if($busca_usuario_na_carona == null){

			$dados = array(
				'id_viagem' => $id_viagem,
				'id_usuario' => $usuario['id_usuario'],
				'nome' => $usuario['nome']
			);

			$tipo = 0;

			$result = $this->carona_ml->insereusuario($dados, $tipo);

			if ($result == 2){
		    	$resultado = '<div id="info" class="info_error"><span>Ops! Tente novamente</span></div>';
		    }else if($result == 1){
		    	$resultado = '<div id="info" class="info_sucesso"><span>Solicitacao enviada com sucesso</span></div>';	
		    }
			
			$resposta = json_encode($resultado);
            echo $resposta;
		}
	}
    
	function excluir_viagem($id_viagem){

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($usuario['id_usuario'] == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$result = $this->db->viagem_ml->excluirviagem($id_viagem);

			if ($result == true){
				$resultado = '<div id="info" class="info_sucesso">
		    				<span>Viagem excluida com sucesso</span>
                    	</div>';
			}else{
				$resultado = '<div id="info" class="info_sucesso">
		    				<span>Ops! Tente novamente</span>
                    	</div>';
			}
		}else{
			$resultado = '<div id="info" class="info_sucesso">
		    				<span>Você não pode fazer isso</span>
                    	</div>';
		}

		$resposta = json_encode($resultado);
        echo $resposta;
	}
    
    function inserir_usuario_na_carona($id_viagem, $id_usuario){

		$dados['id_usuario'] = $id_usuario;
		$dados['nome'] = $this->usuario_ml->get_name_user($id_usuario);;
		$dados['id_viagem'] = $id_viagem;

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];

		$tipo = 1;

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($usuario['id_usuario'] == $buscaviagem[0]->id_usuario){

			$result = $this->carona_ml->insereusuario($dados, $tipo);

			if ($result == 1){
				$resultado = '<div id="info" class="info_sucesso">
			    				<span><b>'.$dados['nome'].'</b> inserido(a) com sucesso</span>
	                    	</div>';
				$this->usuario_ml->set_notification($usuario['id_usuario'], $result['access_token']);
			}else{
				$resultado = '<div id="info" class="info_sucesso">
			    				<span>Ops! Tente novamente</span>
	                    	</div>';
			}
        }else{
        	$resultado = '<div id="info" class="info_sucesso">
		    				<span>Você não pode fazer isso</span>
                    	</div>';
        }
		
		$resposta = json_encode($resultado);
        echo $resposta;
    }

    function remover_usuario_da_carona($id_viagem, $id_usuario){

		$dados['id_usuario'] = $id_usuario;
		$dados['nome'] = $this->usuario_ml->get_name_user($id_usuario);;
		$dados['id_viagem'] = $id_viagem;

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($usuario['id_usuario'] == $buscaviagem[0]->id_usuario){

			$result = $this->carona_ml->excluirusuario($dados);

			if ($result == 1){
				$resultado = '<div id="info" class="info_sucesso">
			    				<span><b>'.$dados['nome'].'</b> removido(a) com sucesso</span>
	                    	</div>';
			}else{
				$resultado = '<div id="info" class="info_sucesso">
			    				<span>Ops! Tente novamente</span>
	                    	</div>';
			}
        }else{
        	$resultado = '<div id="info" class="info_sucesso">
		    				<span>Você não pode fazer isso</span>
                    	</div>';
        }

		$resposta = json_encode($resultado);
        echo $resposta;
    }

    function fecharviagem($id_viagem){

		$user = $this->usuario_ml->get_user();                
		$usuario['id_usuario'] = $user['facebook_uid'];

		$buscaviagem = $this->viagem_ml->buscaviagem($id_viagem);

		if ($usuario['id_usuario'] == $buscaviagem[0]->id_usuario){
			//Usuario criou a viagem
			$result = $this->viagem_ml->fecharviagem($id_viagem);

			if ($result == true){
				$resultado = '<div id="info" class="info_sucesso">
		    				<span>Passageiros abordo. Boa viagem!</span>
                    	</div>';
			}else{
				$resultado = '<div id="info" class="info_sucesso">
		    				<span>Ops! Tente novamente</span>
                    	</div>';
			}
		}else{
			$resultado = '<div id="info" class="info_sucesso">
		    				<span>Você não pode fazer isso</span>
                    	</div>';
		}

		$resposta = json_encode($resultado);
        echo $resposta;
    }
