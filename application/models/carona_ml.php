<?php
class Carona_ml extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->model('viagem_ml');
    }
    //remove the force ssl
    function insereusuario($viagem, $usuario){

		$result = $this->db->select('id_viagem, id_usuario')
						  ->where('id_usuario', $usuario)
						  ->where('id_viagem', $viagem)->get('tb_carona')->result();
		
		if($result != null){
			return 2;
		}else{
			$insere = array(
				'id_viagem' => $viagem,
				'id_usuario' => $usuario,
				'confirmada' => '0');
			$this->db->insert('tb_carona', $insere);

			return 1;
		}
	}

	function excluirusuario($viagem, $usuario){
		$result = $this->db->select('id_viagem, id_usuario')
						  ->where('id_usuario', $usuario)
						  ->where('id_viagem', $viagem)->get('tb_carona')->result();
		
		if($result != null){
			return 2;
		}else{
			$this->db->where('id_viagem', $viagem)
					 ->where('id_usuario', $usuario)
					 ->delete('tb_carona');
			return 1;
		}
	}

	function buscacarona($id_viagem, $tipo){
		
		if ($tipo == 1){
			$result = $this->db->select()
						   ->where('id_viagem', $id_viagem)
						   ->get('tb_carona')->result();	
		}else{
			$result = $this->db->select()
						   ->where('id_viagem', $id_viagem)
						   ->where('confirmada', '1')
						   ->get('tb_carona')->result();	
		}
		return $result;
	}
}
?>
