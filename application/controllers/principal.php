<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {

	function __construct() {
		parent::__construct(); 
		$this->auth->is_logged_in();
	}

	function index() {
		//force_ssl();
        /*Captura o usuario e a sessao*/
		$result = $this->usuario_ml->get_access_token();
           
        /*Verifica se o usuario esta conectado ao facebook*/
		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}

		$user = $this->usuario_ml->get_user();                
		
		$usuario['id_usuario'] = $user['facebook_uid'];

		$this->usuario_ml->set_user($usuario);

		/*Atualiza status da viagem*/
		$this->viagem_ml->atualizaViagens();

		/*Carrega a view principal*/
		$this->load->view('principal_vw');
	}
    
	/*Cria viagem*/
	function ofertar() {
		force_ssl();
		$result = $this->usuario_ml->get_access_token();

		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}

		$user = $this->usuario_ml->get_user();                
		$id_usuario = $user['facebook_uid'];

	    /*Oferecer carona indica que o soliciatante � o motorista*/
		$params = array(
		    'id_usuario' => $id_usuario,
		    'id_motorista' => $id_usuario,
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
		    //$result = $this->usuario_ml->postToWall($params);
		    
		    $resposta = '<div id="info" class="info_sucesso">
		    				<span>Carona criada com sucesso!</span>
                    	</div>';
            echo $resposta;
		}else{
		    $resposta = '<div id="info" class="info_error">
		    				<span>Verifique os dados e envie novamente</span>
                    	</div>';
            echo $resposta;
		}
	}

	function solicitar() {
		force_ssl();
		$result = $this->usuario_ml->get_access_token();

		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}

		$user = $this->usuario_ml->get_user();                
		$id_usuario = $user['facebook_uid'];

	    /*Oferecer carona indica que o soliciatante � o motorista*/
		$params = array(
		    'id_usuario' => $id_usuario,
		    'id_motorista' => 0,
		    'solicitante' => 1, #padrao solicitar
		    'status' => 1, 
		    'origem' => $this->input->post('origem'),
		    'destino' => $this->input->post('destino'),  
		    'data' => $this->input->post('data'),
		    'hora' => $this->input->post('hora'),
		    'obs' => $this->input->post('obs')
		);

		if($this->viagem_ml->setViagem($params)==true){
		    //aqui chama a funcao para postar no mural, somente apos a viagem ser criada
		    //$result = $this->usuario_ml->postToWall($params);
		    
		    $resposta = '<div id="info" class="info_sucesso">
		    				<span>Carona solicitada com sucesso!</span>
                    	</div>';
            echo $resposta;
		}else{
		    $resposta = '<div id="info" class="info_error">
		    				<span>Verifique os dados e envie novamente</span>
                    	</div>';
            echo $resposta;
		}
	}
	
	//quem exibe o historico é o Historico extends Controller
	function minhasViagens(){
		force_ssl();
		$result = $this->usuario_ml->get_access_token();

		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}
		$user = $this->usuario_ml->get_user();                
		$id_usuario = $user['facebook_uid'];

		$data = $this->viagem_ml->minhasViagens($id_usuario);
		$resultado = array("viagem" => $data);
		$resposta = json_encode($resultado);
		echo $resposta;
	}
	
	function amigosViagens(){
		force_ssl();
		$result = $this->usuario_ml->get_access_token();

		if ($result['is_true']) {

			$this->session->set_userdata(array('access_token' => $result['access_token']));

		} else {

			$this->session->set_userdata(array('access_token' => FALSE));
		}

		$user = $this->usuario_ml->get_user();                
		$id_usuario = $user['facebook_uid'];

		/*Captura os amigos do usuario*/
		//$amigos = $this->friends($result['access_token'], $user['facebook_uid']);
		
		$usuario['id_usuario'] = $user['facebook_uid'];

		$this->usuario_ml->set_user($usuario);

		/*for ($i=0; $i<count($amigos['friends']); $i++){
			$friends [$i] = $amigos['friends'][$i]->id;
		} */  

		$friends = Array (
			"639373985",
			"653558478",
			"761784188",
			"1027705458",
			"1038431133",
			"1058463556",
			"1060597729",
			"1107596013",
			"1130122019",
			"1145531078",
			"1190839466",
			"1196192037",
			"1227355671",
			"1267272197",
			"1301650673",
			"1314321176",
			"1317820208",
			"1324117866",
			"1339377044",
			"1351327215",
			"1429747530",
			"1444261657",
			"1471273392",
			"1484950272",
			"1492341074",
			"1509949915",
			"1512773485",
			"1514817389",
			"1520511790",
			"1523755920",
			"1525252031",
			"1540277084",
			"1541382427",
			"1547095261",
			"1552176632",
			"1556791025",
			"1558205687",
			"1573405110",
			"1604992334",
			"1613644736",
			"1639853401",
			"1652265093",
			"1671394496",
			"1676136699",
			"1683401079",
			"1686162406",
			"1726524748",
			"1764234212",
			"1766566080",
			"1776867535",
			"1784505283",
			"1786455076",
			"1814975784",
			"1818636983",
			"1827697884",
			"1830503630",
			"1837823942",
			"1845137307",
			"100000002694027",
			"100000007648412",
			"100000008083323",
			"100000016224119",
			"100000037362683",
			"100000043649143",
			"100000048298896",
			"100000052223954",
			"100000062874175",
			"100000068494397",
			"100000086140580",
			"100000093416064",
			"100000093729132",
			"100000100313194",
			"100000100912670",
			"100000113276066",
			"100000116884995",
			"100000119152770",
			"100000122152689",
			"100000122392508",
			"100000122625619",
			"100000122752833",
			"100000122921991",
			"100000123567093",
			"100000126202599",
			"100000129202861",
			"100000132835890",
			"100000137130049",
			"100000139504817",
			"100000139552719",
			"100000139612671",
			"100000140212527",
			"100000147892751",
			"100000150562737",
			"100000150922442",
			"100000174830967",
			"100000203239974",
			"100000246979221",
			"100000258288867",
			"100000262735639",
			"100000266393539",
			"100000269004227",
			"100000288784849",
			"100000327174860",
			"100000339384934",
			"100000400044604",
			"100000439514326",
			"100000461351295",
			"100000464678843",
			"100000479413695",
			"100000480371766",
			"100000484798185",
			"100000505137376",
			"100000523598078",
			"100000543137939",
			"100000597601798",
			"100000609129453",
			"100000619164959",
			"100000631447400",
			"100000633980156",
			"100000641870083",
			"100000645232789",
			"100000671368831",
			"100000690220538",
			"100000692455494",
			"100000694599307",
			"100000714655014",
			"100000750915191",
			"100000766432145",
			"100000768823727",
			"100000772217922",
			"100000775815394",
			"100000776561948",
			"100000789884252",
			"100000791472147",
			"100000797837915",
			"100000800818639",
			"100000801758241",
			"100000808628133",
			"100000839692383",
			"100000840691246",
			"100000845304388",
			"100000853704219",
			"100000860816541",
			"100000863934123",
			"100000874995719",
			"100000875933163",
			"100000876204734",
			"100000877692701",
			"100000881476164",
			"100000884604064",
			"100000885523518",
			"100000895721833",
			"100000923621529",
			"100000937030719",
			"100000942587906",
			"100000950371957",
			"100000962660623",
			"100000967155326",
			"100000973543518",
			"100000975310013",
			"100000982700634",
			"100000994681736",
			"100001010376524",
			"100001014469079",
			"100001053530392",
			"100001062215111",
			"100001088252405",
			"100001096685058",
			"100001099858316",
			"100001105747912",
			"100001106757055",
			"100001157666849",
			"100001209890989",
			"100001214391874",
			"100001215361806",
			"100001220881942",
			"100001231548930",
			"100001235651988",
			"100001254477784",
			"100001268181209",
			"100001299713962",
			"100001300325844",
			"100001332174548",
			"100001352097165",
			"100001352876760",
			"100001366420593",
			"100001391257785",
			"100001426835273",
			"100001431155522",
			"100001431918772",
			"100001434753764",
			"100001436901094",
			"100001443032329",
			"100001492538970",
			"100001510540474",
			"100001567232218",
			"100001588065525",
			"100001599862159",
			"100001612111191",
			"100001635562343",
			"100001644208332",
			"100001647896352",
			"100001651644754",
			"100001655240926",
			"100001664578500",
			"100001683654384",
			"100001691895011",
			"100001705638559",
			"100001707467871",
			"100001708168956",
			"100001714070907",
			"100001715206080",
			"100001718300721",
			"100001726647041",
			"100001735279333",
			"100001746020997",
			"100001746624586",
			"100001753125848",
			"100001765408630",
			"100001772742559",
			"100001773322757",
			"100001775066902",
			"100001778837510",
			"100001780154697",
			"100001795185155",
			"100001816254640",
			"100001817772595",
			"100001829783556",
			"100001831456366",
			"100001836927867",
			"100001857143248",
			"100001868453738",
			"100001881923692",
			"100001906322608",
			"100001909765909",
			"100001914327346",
			"100001915609686",
			"100001925578420",
			"100001925832275",
			"100001932606245",
			"100001933403197",
			"100001940062209",
			"100001942257667",
			"100001944774365",
			"100001964868539",
			"100001968092458",
			"100001982222531",
			"100001984735537",
			"100001985301473",
			"100001998417251",
			"100001999594559",
			"100002010693298",
			"100002013113028",
			"100002016975571",
			"100002019752885",
			"100002024004866",
			"100002025684975",
			"100002032212327",
			"100002033748298",
			"100002043163342",
			"100002047789257",
			"100002050125903",
			"100002057713232",
			"100002059278858",
			"100002061782155",
			"100002072992145",
			"100002076256897",
			"100002083087375",
			"100002083744644",
			"100002092803012",
			"100002101904481",
			"100002103826032",
			"100002103998740",
			"100002106187965",
			"100002107631269",
			"100002111891506",
			"100002131621607",
			"100002134932418",
			"100002139746040",
			"100002141087074",
			"100002142782296",
			"100002148289810",
			"100002149728803",
			"100002158680592",
			"100002158740643",
			"100002160391234",
			"100002163073474",
			"100002170792007",
			"100002171584934",
			"100002176860094",
			"100002179954167",
			"100002182457479",
			"100002187410381",
			"100002196821239",
			"100002199638475",
			"100002211661330",
			"100002214400152",
			"100002224456116",
			"100002225718939",
			"100002226434584",
			"100002228690001",
			"100002230435384",
			"100002231303546",
			"100002236377109",
			"100002239562157",
			"100002241529666",
			"100002243174224",
			"100002244635349",
			"100002245826594",
			"100002246236121",
			"100002247023712",
			"100002248797365",
			"100002250870763",
			"100002256733362",
			"100002258354438",
			"100002258559024",
			"100002261388491",
			"100002261806215",
			"100002261885947",
			"100002262611890",
			"100002263816685",
			"100002268115168",
			"100002273167487",
			"100002273191232",
			"100002274274290",
			"100002276973035",
			"100002277284175",
			"100002277424285",
			"100002282363992",
			"100002282448434",
			"100002282883671",
			"100002285266301",
			"100002286023265",
			"100002286386734",
			"100002289180286",
			"100002292956533",
			"100002300974736",
			"100002304386150",
			"100002304508875",
			"100002307596737",
			"100002311505763",
			"100002313810039",
			"100002316990875",
			"100002317185257",
			"100002318464481",
			"100002322151330",
			"100002322412556",
			"100002328923566",
			"100002339201559",
			"100002343898214",
			"100002346757614",
			"100002350216510",
			"100002351101946",
			"100002353447651",
			"100002354311673",
			"100002360204888",
			"100002366656242",
			"100002367266911",
			"100002374211765",
			"100002374441613",
			"100002382174900",
			"100002383193482",
			"100002384502961",
			"100002386496227",
			"100002388172934",
			"100002408618613",
			"100002411980188",
			"100002415245252",
			"100002439279368",
			"100002440861938",
			"100002447063492",
			"100002449133967",
			"100002451277726",
			"100002452471233",
			"100002454562133",
			"100002459743851",
			"100002463260275",
			"100002465295132",
			"100002467202372",
			"100002501334672",
			"100002501961181",
			"100002507512756",
			"100002511546923",
			"100002515917763",
			"100002518554805",
			"100002532510140",
			"100002535469023",
			"100002536266657",
			"100002537892752",
			"100002544789292",
			"100002548820291",
			"100002553997175",
			"100002562207256",
			"100002564076530",
			"100002565104445",
			"100002566365812",
			"100002571376870",
			"100002595121234",
			"100002595799012",
			"100002599750814",
			"100002605471251",
			"100002607948468",
			"100002613913279",
			"100002617500557",
			"100002618168884",
			"100002620579196",
			"100002621865141",
			"100002622530665",
			"100002633578176",
			"100002633973452",
			"100002641964323",
			"100002645438363",
			"100002653206583",
			"100002657513494",
			"100002662114152",
			"100002663224991",
			"100002663462646",
			"100002668489418",
			"100002668895449",
			"100002689641466",
			"100002693466739",
			"100002697667266",
			"100002720874072",
			"100002723697427",
			"100002736589327",
			"100002762198625",
			"100002777391898",
			"100002780617330",
			"100002785702828",
			"100002789833489",
			"100002800804247",
			"100002814427874",
			"100002817232719",
			"100002825928479",
			"100002849157742",
			"100002850378712",
			"100002858816726",
			"100002871101561",
			"100002918270710",
			"100002925196384",
			"100002928338848",
			"100002929878938",
			"100002931489518",
			"100002939184027",
			"100002945254822",
			"100002951271511",
			"100002959653062",
			"100002970970929",
			"100002980483361",
			"100002982540834",
			"100003007158007",
			"100003009424313",
			"100003014397010",
			"100003019927262",
			"100003048716236",
			"100003069428297",
			"100003073663036",
			"100003081054275",
			"100003099383449",
			"100003101278579",
			"100003102303298",
			"100003139308211",
			"100003142330093",
			"100003162671547",
			"100003165709814",
			"100003168472837",
			"100003176967449",
			"100003190242678",
			"100003197734691",
			"100003256793520",
			"100003267326975",
			"100003272894093",
			"100003285279310",
			"100003293276431",
			"100003300770080",
			"100003341973172",
			"100003342971953",
			"100003353748371",
			"100003357300157",
			"100003362525727",
			"100003389893877",
			"100003391076072",
			"100003452693858",
			"100003464711246",
			"100003467647327",
			"100003489131091",
			"100003508461813",
			"100003509112043",
			"100003512359867",
			"100003516250630",
			"100003519873427",
			"100003527553450",
			"100003533813686",
			"100003541511138",
			"100003541925125",
			"100003554471574",
			"100003554914444",
			"100003560743021",
			"100003564500233",
			"100003566068506",
			"100003579410272",
			"100003610224309",
			"100003616428848",
			"100003616768803",
			"100003647123068",
			"100003655779173",
			"100003684688689",
			"100003702908297",
			"100003713617267",
			"100003720589372",
			"100003728601525",
			"100003731185161",
			"100003738166136",
			"100003740380886",
			"100003755531054",
			"100003770303290",
			"100003771933230",
			"100003773336937",
			"100003784062158",
			"100003791980408",
			"100003797282438",
			"100003804819201",
			"100003812595939",
			"100003817687530",
			"100003821649879",
			"100003870933785",
			"100003871181092",
			"100003901752972",
			"100003912250693",
			"100003914718139",
			"100003922837036",
			"100003965692182",
			"100003971647522",
			"100004012825486",
			"100004044367315",
			"100004090955442",
			"100004102313209",
			"100004122217185",
			"100004134841365",
			"100004183683694",
			"100004200131587",
			"100004201663057",
			"100004222082491"
			);


		$data = $this->viagem_ml->amigosViagens($friends, 1);
		if ($data == null){
			$resultado = array();
		}else{
			$resultado = array("viagem" => $data);
		}
		$resposta = json_encode($resultado);
		echo $resposta;
	}

	function buscaviagem($id){
		$result = $this->viagem_ml->buscaviagem($id);
		$resultado = array('viagem' => $result);
		$resposta = json_encode($resultado);
		echo $resposta;
	}

	function excluirviagem($id_viagem){
		$result = $this->viagem_ml->excluirviagem($id_viagem);

	    $resultado = 'Carona excluida com sucesso';
	    $resposta = json_encode($resultado);
        echo $resposta;
	}

	function buscacarona($id){
		$user = $this->usuario_ml->get_user();                
		$usuario = $user['facebook_uid'];

		$criador_viagem = $this->viagem_ml->buscaviagem($id);
		
		
		if ($usuario == $criador_viagem[0]->id_usuario){
			//Usuario criou a viagem
			$tipo = 1;
		}else{
			//Usuario nao criou a viagem
			$tipo = 2;
		}

		$result = $this->carona_ml->buscacarona($id, $tipo);

		$resultado = array('viagem' => $result);
		$resposta = json_encode($resultado);
		echo $resposta;
	}

	function participarcarona($id){

		$user = $this->usuario_ml->get_user();                
		$usuario = $user['facebook_uid'];

		$criador_viagem = $this->viagem_ml->buscaviagem($id);		

		if ($usuario == $criador_viagem[0]->id_usuario){
		    
			$resultado = 'Você já está participando dessa carona';
			$resposta = json_encode($resultado);
            echo $resposta;

		}else{
			$result = $this->carona_ml->insereusuario($id, $usuario);

			if ($result == 2){
		    	$resultado = 'Desculpe por isso. Tente novamente';
		    }else if($result == 1){
		    	$resultado = 'Passageiro inserido com sucesso';	
		    }
			
			$resposta = json_encode($resultado);
            echo $resposta;
		}
	}
    
    function excluirUsuarioCarona($id){
    	$user = $this->usuario_ml->get_user();                
		$usuario = $user['facebook_uid'];

		$criador_viagem = $this->viagem_ml->buscaviagem($id);		

		if ($usuario == $criador_viagem[0]->id_usuario){

		    $result = $this->carona_ml->excluircarona($id, $usuario);

		    if ($result == 2){
		    	$resposta = 'Desculpe por isso. Tente novamente';
		    }else if($result == 1){
		    	$resposta = 'Usuario removido com sucesso';
		    }
		    
            echo $resposta;

		}else{
			$resposta = 'Voce não pode fazer isso';
            echo $resposta;
		}
    }

	//some example functions
	//function me is DRY and dynamic to show as example
	//object: likes, home, feed, movies, music, books, notes, permissions, photos, albums, videos, uploaded, events, groups, checkins, locations, etc.
	//https://developers.facebook.com/docs/reference/api/
	function me($object = NULL) {
		if ($object == NULL) {
			$this->index();
		} else {
			$this->load->model('usuario_ml');
			$result = $this->usuario_ml->get_facebook_object($object, $this->session->userdata('facebook_uid'), $this->session->userdata('access_token'));
			if ($result['is_true']) {
				$data['objects'] = $result['data'];
			} else {
				$data['error_message'] = $result['message'];
				$data['objects'] = array();
			}
			return $data;
			/*$data['page'] = 'objects_view';
			$this->load->view('template', $data);*/
		}
	}
        
	//example function
	function friends($token, $uid) {
		$this->load->model('usuario_ml');
		$result = $this->usuario_ml->get_facebook_object('friends', 
														 $uid, 
														 $token);

		if ($result['is_true']) {
			$data['friends'] = $result['data'];
		} else {
			$data['error_message'] = $result['message'];
			$data['friends'] = array();
		}
		
		return $data;
                
		/*$data['page'] = 'friends_view';
		$this->load->view('template', $data);*/
	}
	
	//example function
	function likes() {
		$this->load->model('fizzlebizzle');
		$result = $this->fizzlebizzle->get_facebook_object('likes', $this->session->userdata('facebook_uid'), $this->session->userdata('access_token'));
		
		if ($result['is_true']) {
			$data['likes'] = $result['data'];
		} else {
			$data['error_message'] = $result['message'];
			$data['likes'] = array();
		}
		
		$data['page'] = 'likes_view';
		$this->load->view('template', $data);
	}
}