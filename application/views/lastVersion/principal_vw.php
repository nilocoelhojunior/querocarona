<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" content="IE=7" content="IE=EmulateIE7" charset="utf-8">
        
        <title>Quero Carona Facebook</title>

        <META NAME="DESCRIPTION" CONTENT="Ofereça e solicite carona para seus amigos no facebook">
        <META NAME="ABSTRACT" CONTENT="Compartilhe carona com seus amigos no facebook">
        <META NAME="KEYWORDS" CONTENT="carona,querocarona,caronas,viagem,sustentabilidade,mundoverde,amigos,facebook,caroneiro,caronabrasil,oferecer,solicitar,meioambiente,engarrafamento,carro">
        <META NAME="ROBOT" CONTENT="All">
        <META NAME="RATING" CONTENT="general">
        <META NAME="DISTRIBUTION" CONTENT="global">
        <META NAME="LANGUAGE" CONTENT="PT">

            
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/jquery-ui-1.8.20.custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/script.js"></script>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/jquery-ui-1.8.20.custom.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/styles.css">   

        <!--[if gte IE 9]>
          <style type="text/css">
            .gradient {
               filter: none;
            }
          </style>
        <![endif]-->

        <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=186499281485398";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

    </head>  
    
    <body>        
        <div class="canvas">

            <div id="fb-root"></div>

            <div id="" class="querocarona">
                <div id="social-like">
                    <div class="fb-like" data-href="https://apps.facebook.com/querocarona/" data-send="true" data-width="450" data-show-faces="true" data-font="arial"></div>
                </div>
            </div>

            <div id="border-form">
                
                <div id="form" class="layer2">  
                    
                    <div id="info" class="info_sucesso"><div id="info_conteudo_sucesso"></div></div>
                    <div id="info" class="info_error"><div id="info_conteudo_error"></div></div>


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
                        <button class="btn_amigos_viagens" id="btn_amigos_viagens" title="Viagens dos meus amigos" onclick="exibeviagem(1);"></button>
                        <button class="btn_minhas_viagens" id="btn_minhas_viagens" title="Minhas Viagens" onclick="exibeviagem(2);"></button>
                        <input id="field-pesquisa" name="pesquisar" placeholder="Caronas, pessoas ou lugares" type="text" title="Busque por caronas, pessoas ou lugares aqui. Ex: Escola, João"/>
                    </div>

                    <div class="viagem">
                        <div id="loader_viagem" style="display: block; position: absolute; padding-left:17px; margin-top: 15px;">
                            <img src="<?php echo base_url();?>/assets/img/loader.gif" style="width: 25px;">
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div id="display-modal">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel" style="margin-left: 34px; color: white;"></h3>
                    </div>
                    <div class="modal-body">
                        <div id="modal-viagem"></div>
                        <div id="modal-viagem-obs">
                            <h4>Obs</h4>
                            <div id="modal-viagem-exibe-obs"></div>
                        </div>

                        <div id="modal-confirmados-header">
                            <h4>Passageiros</h4>
                            <div id="modal-confirmados">
                                <ul id="modal-confirmados-detalhe">
                                </ul>
                            </div>
                        </div>

                        <div id="modal-solicitados-header">
                            <h4>Solicitações</h4>
                            <div id="modal-confirmados">
                                <ul id="modal-solicitados-detalhe">
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn" id="botao_voltar" data-dismiss="modal" aria-hidden="true">Voltar</button>
                    </div>

                </div>

                <div id="loader-modal"></div>
            </div>
            
        </div>
    </body>
</html>