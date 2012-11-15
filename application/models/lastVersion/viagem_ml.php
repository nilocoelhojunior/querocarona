<?php
class Viagem_ml extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }

    function setViagem($params){
       
      #Valida DATA setada pelo user
       $dt = $this->input->post('data');
       $ano = substr($dt, 6,9);
       $dia = substr($dt, 0,2);
       $mes = substr($dt, 3,2);
       $r = $ano."-".$mes."-".$dia;
       $b = strtotime($r);
       
       # DATA system
       $atual = date("Y-m-d");
       $a2 = substr($atual, 0,4);
       $m2 = substr($atual, 5,2);
       $d2 = substr($atual, 8,9);
       $atual2 = $a2."-".$m2."-".$d2;
       $a = strtotime($atual2);
       
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

       /*echo "dt:".$dt." ";
       echo "ano:".$ano." ";
       echo "mes:".$mes." ";
       echo "dia:".$dia." ";
       echo "r:".$r." ";
       echo "d_user:".$b." ";
       echo "d_sys:".$a." ";

       echo "h_user:".$h3."/";
       echo "d_sys:".$horaAtual3."/";*/

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

       if ($b >= $a){
          $this->db->insert('tb_viagem', $result);
          return true;
       }else if ($b == $a){
           if($h3 >= $horaAtual3){
                $this->db->insert('tb_viagem', $result);
                return true;
           }
           return false;
       }
       return false;
    }
    
    function excluirviagem($id_viag){
      $id_viagem = array('id_viagem' => $id_viag);
      $this->db->delete('tb_carona', $id_viagem);
      $this->db->delete('tb_viagem', $id_viagem);
      return true;
    }

    function fecharviagem($id_viagem){
      $status = array('status'=>0);
      $this->db->where('id_viagem', $id_viagem)->update('tb_viagem', $status);
      return true;
    }

    function buscaViagem($id_viagem){
        return $this->db->select('*')->where('id_viagem',$id_viagem)->get('tb_viagem')->result();
    }
    
    function amigosviagens($lista,$tipo){
        $this->db->select('*')->where('status',$tipo);
        $this->db->where_in('id_usuario',$lista);
        $this->db->order_by('data', 'DESC');
        return $this->db->get('tb_viagem')->result();
    }
    
    function minhasViagens($id_usuario){
       $a = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
       $teste = array('data >' => $a, 'id_usuario' => $id_usuario);
       return $this->db->select('*')->where($teste)->order_by('data', 'DESC')->get('tb_viagem')->result();
    }

    function atualizaViagens(){
      $atual = date("Y-m-d");
      $value = array('status' => 0);

      $result = $this->db->where('data <', $atual)->update('tb_viagem', $value);
    }
}
?>