<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" content="IE=7" content="IE=EmulateIE7" charset="utf-8">
        
        <title>Quero Carona Facebook</title>	
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/jquery-ui-1.8.20.custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/script.js"></script>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/jquery-ui-1.8.20.custom.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/styles.css">	
    </head>  
    
    <body>        
        <div class="canvas">
            <div id="" class="querocarona"></div>
            <div id="border-form">
                
                <div id="form" class="layer2">  
                    
                    <div id="loader" style="display: none; position: absolute; padding-left:17px; margin-top: 15px;">
                        <img src="<?php echo base_url();?>/assets/img/loader.gif" style="width: 20px;">
                    </div>
                    
                    <form name="form" class="formulario" id="formulario" method="post" onsubmit="return false;">
                        <label class="laborigem" for="origem">De:
                            <input id="origem" name="origem" placeholder="Ex: ifpi, centro..." type="text" />
                        </label>
                        <label class="labdestino" for="destino">Para:
                            <input id="destino" name="destino" placeholder="Ex: ifpi, centro.." type="text" />
                        </label>
                        <label class="labdata" for="data">Data
                            <input id="data" name="data" placeholder="__/__/____" type="text" />
                        </label>
                        <label class="labhora" for="hora">Hora
                            <input name="hora" id="hora" type="text" value="" placeholder="__:__  24h" />
                        </label>
                        <label class="labobs" for="obs">Anota&ccedil;&otilde;es
                            <textarea id="obs" name="obs" type="text" rows=3 placeholder="Ex: ajuda com a gasolina.." style="resize: none;"></textarea>
                        </label>
                        <div class="btn-form" >
                            <input class="btn btn-primary" id="chk_ofertar" value="Oferecer" title="Oferte uma carona =D" type="submit"/>
                            <input class="btn btn-primary" id="chk_solicitar" value="Solicitar" title="Solicite uma carona =D" type="submit"/>
                        </div>
                    </form>
                </div>  	  

                <div id="search" class="layer3">		
                    <div class="btn-pesquisa">	
                        <button class="btn_amigos_viagens" id="btn_amigos_viagens" title="Viagens dos meus amigos"></button>
                        <button class="btn_minhas_viagens" id="btn_minhas_viagens" title="Minhas Viagens"></button>
                        <input id="field-pesquisa" name="pesquisar" placeholder="Buscar "type="text" title="Pesquise por caronas aqui. Ex: Ifpi, Filipe"/>
                    </div>

                    <div class="viagem">
                        <div id="loader_viagem" style="display: none; position: absolute; padding-left:17px; margin-top: 15px;">
                            <img src="<?php echo base_url();?>/assets/img/loader.gif" style="width: 25px;">
                        </div>
                    </div>
                </div>

        </div>
        <div class="fundo_transparente" style="display:none;">
        </div>
        <div id="exp_modal">
                <div id="conteudo_modal">
                    <div id="cabecalho_modal"></div>
                    <div id="detalhe_modal"></div>
                    <div id="obs_modal"></div>
                    <div title="Passageiros confirmados" id="passageiros_modal"></div>
                    <div title="Solicitações recentes" id="solicitacoes_modal"></div>
                </div>
                <div id="modal_loader" style="display: none; position: absolute; padding-left:17px; margin-top: 15px;">
                    <img src="<?php echo base_url();?>/assets/img/loader.gif" style="width: 20px;">
                </div>
                <div id="btn_modal">
                </div>
            </div>
        <div class="links" id="link">
        </div>
    </div>
</body>
</html>