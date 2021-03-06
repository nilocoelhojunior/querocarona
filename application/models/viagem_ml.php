<?php
class Viagem_ml extends CI_Model{
    
    function __construct() {
        parent::__construct();

        date_default_timezone_set("Brazil/East");
    }

    function setViagem($params){
      // Implementar data por meio do framework ...
      #Valida DATA setada pelo user
       $dt = $this->input->post('data');
       $ano = substr($dt, 6,9);
       $dia = substr($dt, 0,2);
       $mes = substr($dt, 3,2);
       $r = $ano."-".$mes."-".$dia;
       $data_digitada = strtotime($r);
       
       # DATA system
       $atual = date("Y-m-d");
       $ano_atual = substr($atual, 0,4);
       $mes_atual = substr($atual, 5,2);
       $dia_atual = substr($atual, 8,9);
       $atual2 = $ano_atual."-".$mes_atual."-".$dia_atual;
       $data_atual = strtotime($atual2);
       
       #Valida HORA setada pelo user
       $h = $this->input->post('hora');       
       $hora = substr($h, 0,2);
       $minuto = substr($h, 3,4);
       $h2 = $hora.":".$minuto;
       $h3 = strtotime($h2);

       #HORA system
       $horasis = mktime(date("H"), date("i")+10);
       $horaAtual = date("H:i", $horasis);
       $ho2 = substr($horaAtual, 0,2);
       $min2 = substr($horaAtual, 3,4);
       $horaAtual2 = $ho2.":".$min2;
       $horaAtual3 = strtotime($horaAtual2);

       $result = array(
              'id_usuario' => $params['id_usuario'],
              'nome' => $params['nome'],
              'solicitante' => $params['solicitante'],  
              'origem' => $params['origem'],
              'destino' => $params['destino'],  
              'data' => $r,
              'hora' => $h2,
              'obs' => $this->input->post('obs'), 
              'status' => $params['status']);

       if ($data_digitada > $data_atual){
          $this->db->insert('tb_viagem', $result);
          $id = mysql_insert_id();
          return $id;
       }else if ($data_digitada == $data_atual){
           if($h3 >= $horaAtual3){
                $this->db->insert('tb_viagem', $result);
                $id = mysql_insert_id();
                return $id;
           }
           return false;
       }
       return false;
    }
        
    // Exclus�o da viagem com o parametro da viagem ...
    function excluirviagem($id_viag){
      $id_viagem = array('id_viagem' => $id_viag);
      $this->db->delete('tb_carona', $id_viagem);
      $this->db->delete('tb_viagem', $id_viagem);
      return true;
    }

    // Viagem Fechada ... e atualizada com o status 0 (inativa) ou 1 (ativa) !!
    function fecharviagem($id_viagem){
      $status = array('status'=>0);
      $this->db->where('id_viagem', $id_viagem)->update('tb_viagem', $status);
      return true;
    }
	
    // Vai para a viagem selecionada pelo usuario (clique do mouse) 
    function buscaViagem($id_viagem){
        return $this->db->select('*')->where('id_viagem',$id_viagem)->get('tb_viagem')->result();
    }
    
    // Busca todas as viagens Ativas da tabela
    function busca_todas_viagens(){
        return $this->db->select('*')->where('status', 1)->get('tb_viagem')->result(); 
    }
    
	  // Carrega todas as viagens Ativas de meus amigos.
    function viagensDeMeusAmigos($lista, $tipo){
        $this->db->select('*')->where('status', $tipo);
        $this->db->where_in('id_usuario', $lista);
        $this->db->order_by('data', 'DESC');
        return $this->db->get('tb_viagem')->result();
    }
    
    // Busco todas as minhas viagens criadas dentro de 30 dias
    function minhasViagens($id_usuario){
       $a = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
       $teste = array('data >' => $a, 'id_usuario' => $id_usuario);
       return $this->db->select('*')->where($teste)->order_by('data', 'DESC')->get('tb_viagem')->result();
    }
    
	  //Atualiza todas as viagens de status 1(ativada) para 0(desativada)
    function atualizaViagens(){
      $atual = date("Y-m-d");
      $value = array('status' => 0);
      $result = $this->db->where('data <', $atual)->update('tb_viagem', $value);
    }

    function buscadinamica($palavra, $id){
      $ids = join(',', $id);

      $tipo = gettype($id);

      if ($tipo == 'string'){
        
        $query = $this->db->query("SELECT DISTINCT a.id_viagem, a.id_usuario, a.nome, a.solicitante, a.origem, a.destino, a.data, a.hora, a.obs, a.status FROM tb_viagem a INNER JOIN tb_viagem b ON a.status = 1 AND a.id_usuario IN ({$id}) WHERE a.nome LIKE '%{$palavra}%' OR a.destino LIKE '%{$palavra}%' OR a.origem LIKE '%{$palavra}%'")->result();
      }else{

        $query = $this->db->query("SELECT DISTINCT a.id_viagem, a.id_usuario, a.nome, a.solicitante, a.origem, a.destino, a.data, a.hora, a.obs, a.status FROM tb_viagem a INNER JOIN tb_viagem b ON a.status = 1 AND a.id_usuario IN ({$ids}) WHERE a.nome LIKE '%{$palavra}%' OR a.destino LIKE '%{$palavra}%' OR a.origem LIKE '%{$palavra}%'")->result();  
      }

      return $query;
    }
}
?>