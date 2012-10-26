<?php
class Carona_ml extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->model('viagem_ml');
    }
    //remove the force ssl
    function insereusuario($dados, $tipo){

    	if ($tipo == 0){
			$result = $this->db->select('id_viagem, id_usuario')
							  ->where('id_usuario', $dados['id_usuario'])
							  ->where('id_viagem', $dados['id_viagem'])->get('tb_carona')->result();
			
			if($result != null){

				return 2;

			}else{
				
				$insere = array(
					'id_viagem' => $dados['id_viagem'],
					'id_usuario' => $dados['id_usuario'],
					'nome' => $dados['nome'],
					'confirmada' => '0'
				);

				$this->db->insert('tb_carona', $insere);

				return 1;

			}
		}else if($tipo == 1){
				$status = array('confirmada'=>$tipo);
      			$this->db->select('id_viagem, id_usuario')
						  ->where('id_usuario', $dados['id_usuario'])
						  ->where('id_viagem', $dados['id_viagem'])->update('tb_carona', $status);

				return 1;
		}
		
	}

	function excluirusuario($dados){
		$result = $this->db->select('id_viagem, id_usuario')
							  ->where('id_usuario', $dados['id_usuario']) // 
							  ->where('id_viagem', $dados['id_viagem'])->get('tb_carona')->result();
		
		if($result != null){
			$this->db->where('id_usuario', $dados['id_usuario'])
					 ->where('id_viagem', $dados['id_viagem'])
					 ->delete('tb_carona');
			return 1;	// Se ele achou o usuario ...
		}else{
			return 2;	// Negativo...
		}
	}

	function buscacarona($id_viagem, $tipo){
		$user = $this->usuario_ml->get_user();                
		$usuario = $user['facebook_uid'];

		if ($tipo == 1){
			$result = $this->db->select()
						   ->where('id_viagem', $id_viagem)
						   ->get('tb_carona')->result();	
		}else{
			//busco minhas solicitações na viagem 
			$condicao = 'id_viagem = '.$id_viagem.' AND confirmada=1 OR id_viagem = '.$id_viagem.' AND id_usuario='.$usuario.'';
			$result = $this->db->select()
						   ->where($condicao)
						   ->get('tb_carona')->result();	
		}
		return $result;
	}

	function busca_usuario_na_carona($id_viagem, $id_usuario){
		return $this->db->select()
				 ->where('id_viagem', $id_viagem)
				 ->where('id_usuario', $id_usuario)
				 ->get('tb_carona')->result();
	}
	
}
?>
