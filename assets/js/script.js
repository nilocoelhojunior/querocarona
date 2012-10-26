function mensagem(tipo, mensagem){

    $('#info_conteudo_sucesso').html('');
    $('#info_conteudo_error').html('');

    if (tipo == 1){
        $('#info_conteudo_sucesso').append(mensagem);
        $('.info_sucesso').fadeIn(300).delay(3000).slideUp(400);
    }else if (tipo == 2){
        $('#info_conteudo_error').append(mensagem);
        $('.info_error').fadeIn(300).delay(3000).slideUp(400);;
    }
}

function exibeviagem(tipo){
    $("p").remove();
    $("span").remove();

    jQuery.error = console.error;

    tipo;

    if(tipo == 1){
        $(".btn_amigos_viagens").switchClass( "btn_amigos_viagens", "btn_amigos_viagens_ativa", 1);

        if($("#btn_minhas_viagens").hasClass('btn_minhas_viagens_ativa')){            
            $( ".btn_minhas_viagens_ativa" ).switchClass( "btn_minhas_viagens_ativa", "btn_minhas_viagens", 1);
        }
        
    }else if(tipo == 2){
        $(".btn_minhas_viagens").switchClass( "btn_minhas_viagens", "btn_minhas_viagens_ativa", 1);

        if($("#btn_amigos_viagens").hasClass('btn_amigos_viagens_ativa')){
            $( ".btn_amigos_viagens_ativa" ).switchClass( "btn_amigos_viagens_ativa", "btn_amigos_viagens", 1);
        }
        
    }

    iurl =  "principal/exibeviagem/"+tipo;

    $.ajax({

            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){ 
                $('#loader_viagem').hide();

                if(resposta.tipo == 1){
                    $('.viagem').append('<span style="position: absolute; margin-left: 20px; margin-top: 20px; font-size: 16px;">'+resposta.viagem+'</span>');
                }else if(resposta.tipo == 2){
                    $.each(resposta, function(i, valor){
                        $.each(valor, function(n, nome){
                            console.log(nome);
                            if(nome.status == 0){
                                $('.viagem').append('<p style="opacity: 0.5;" onclick="modalviagens('+nome.id_viagem+');"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/><b>'+nome.nome+'</b> '+nome.tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+nome.data+'</b> às <b>'+nome.hora+'</b>.</p>');
                            }else{
                                $('.viagem').append('<p onclick="modalviagens('+nome.id_viagem+');"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/><b>'+nome.nome+'</b> '+nome.tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+nome.data+'</b> às <b>'+nome.hora+'</b>.</p>');
                            }
                        });
                    });            
                }
            },

            beforeSend: function(){
                $('#loader_viagem').show();
            }
    });
}

function solicitarcarona(tipo){
    $('#myModal').modal('hide');

    iurl = "principal/solicitar_carona/"+tipo;

    $.ajax({
            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){
                $('#loader').hide();  

                mensagem(resposta.tipo, resposta.viagem);
            },

            beforeSend: function(){
                $('#loader').show();
            }
    });
}

function efetuarcarona(tipo){

    iurl = "principal/fecharviagem/"+tipo;
    
    $.ajax({
            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){
                $('#loader').hide(); 
                
                mensagem(resposta.tipo, resposta.viagem);
            },

            beforeSend: function(){
                $('#myModal').modal('hide');
                $('#loader_viagem').show();
            }
    });
}

function excluirviagem(tipo){
    
    iurl = "principal/excluir_viagem/"+tipo;
    
    $.ajax({
            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){
                $('#loader').hide(); 
                
                mensagem(resposta.tipo, resposta.viagem);

                $("#btn_minhas_viagens").trigger('click');
            },

            beforeSend: function(){
                $('#myModal').modal('hide');
                $('#loader_viagem').show();
            }
    });
}

function aceitar_passageiro(tipo, tipo2){

    iurl = "principal/inserir_usuario_na_carona/"+tipo+"/"+tipo2;
    
    $.ajax({
            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){
                $('#loader').hide();  
                
                mensagem(resposta.tipo, resposta.viagem);
            },

            beforeSend: function(){
                $('#myModal').modal('hide');
                $('#loader_viagem').show();
            }
    });
}

function recusar_passageiro(tipo, tipo2){

    iurl = "principal/remover_usuario_da_carona/"+tipo+"/"+tipo2;
    
    $.ajax({
            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){
                $('#loader').hide(); 

                mensagem(resposta.tipo, resposta.viagem);
            },

            beforeSend: function(){
                $('#myModal').modal('hide');
                $('#loader_viagem').show();
            }
    });
}

function modalviagens(tipo){

    $('h3').html('');
    $('#modal-viagem-exibe-obs').html('');
    $('#modal-confirmados-detalhe').html('');
    $('.modal-header img').remove();
    $('#display-modal p').remove();
    $('#solicitarcarona').remove();
    $('#excluirviagem').remove();
    $('#solicitarcarona').remove();
    $('#efetuarcarona').remove();
    

    iurl =  "principal/exibecarona/"+tipo;

    $.ajax({

            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){ 
                $('#modal_loader').hide();
                $('#modal-solicitados-header').css({display:"block"});

                $('.modal-header').append('<img src="https://graph.facebook.com/'+resposta.viagem[0].id_usuario+'/picture" width="35" height="35" style="float: left; padding-right: 5px; margin-left: -11px; margin-top: -31px;"/>');
                $('h3').html(resposta.viagem[0].nome);
                $('#modal-viagem').append('<p style="font-size: 13px;"><b>'+resposta.viagem[0].tipo_solicitacao+'</b> carona de <b>'+resposta.viagem[0].origem+'</b> para <b>'+resposta.viagem[0].destino+'</b> dia <b>'+resposta.viagem[0].data+'</b> às <b>'+resposta.viagem[0].hora+'</b>.</p>');
                
                if (resposta.viagem[0].obs == ''){
                    $('#modal-viagem-exibe-obs').append('<p>Nenhuma observação</p>');
                }else{
                    $('#modal-viagem-exibe-obs').append('<p>'+resposta.viagem[0].obs+'</p>');
                }

                if(resposta.tipo == 2){

                    //$('#modal-solicitados-header').css({display:"none"});                    

                    if (resposta.carona == '' || resposta.carona[0].confirmada == ''){

                        $('#modal-confirmados-detalhe').append('<p>Nenhum passageiro confirmado</p>');

                    }else{

                        $.each(resposta.carona, function(i, valor){
                            if(valor.confirmada == 0){
                                $('#modal-solicitados-detalhe').append('<li><div class="conteudo-modal"><img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="35" height="35"/><button id="aceitar_passageiro" class="btn btn-mini btn-link" style="cursor: text;">Aguardando</button></div></li>');                                    
                            }
                            if(valor.confirmada == 1){
                                $('#modal-confirmados-detalhe').append('<li><div class="conteudo-modal"><img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="35" height="35"/></div></li>');
                            }
                        });  
                    }

                }else if(resposta.tipo == 1){

                        c = 0;
                        s = 0;

                        $.each(resposta.carona, function(i, valor){

                            if(valor.confirmada == 1){
                                c++;
                                $('#modal-confirmados-detalhe').append('<li><div class="conteudo-modal"><img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="35" height="35"/></div><button id="recusar_passageiro" class="btn btn-mini btn-link" onclick="recusar_passageiro('+valor.id_viagem+','+valor.id_usuario+')">Recusar</button></li>');
                            }else if(valor.confirmada == 0){
                                s++;
                                $('#modal-solicitados-detalhe').append('<li><div class="conteudo-modal"><img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="35" height="35"/><button id="aceitar_passageiro" class="btn btn-mini btn-link" onclick="aceitar_passageiro('+valor.id_viagem+','+ valor.id_usuario+')">Aceitar</button><button id="recusar_passageiro" class="btn btn-mini btn-link" onclick="recusar_passageiro('+valor.id_viagem+','+valor.id_usuario+')">Recusar</button></div></li>');
                            }
                        });  

                        if (resposta.carona == '' || c == 0){
                            $('#modal-confirmados-detalhe').append('<p>Nenhum passageiro confirmado</p>');
                        }
                        if (resposta.carona == '' || s == 0){
                            $('#modal-solicitados-detalhe').append('<p>Nenhuma solicitação</p>');
                        }
                }

                $('.modal-footer').append(resposta.botao2);
                $('#loader-modal').hide();

                var csopacity = {
                    'opacity': '1',
                    'filter': 'alpha(opacity=100)'
                }   
                
                $('#display-modal').css(csopacity);
            },

            beforeSend: function(){
                $('#loader-modal').show();
            }
    });

    $('#myModal').modal('show');
}

$(function(){

    $('#myModal').modal({
        keyboard: true,
    });

    $('.btn-primary').click(function(){

        iurl = 'principal/criaviagem'+'/'+$(this).attr('id');

        $.ajax({
            type: "POST", 

            url: iurl,

            dataType: "json",

            data: {origem: $('input:text[name=origem]').val(), destino: $('input:text[name=destino]').val(), data: $('input:text[name=data]').val(), hora: $('input:text[name=hora]').val(), obs: $('#obs').val()},

            success: function(resposta){
                console.log(resposta.tipo);
                console.log(resposta.viagem);

                $('#loader').hide();  
                
                mensagem(resposta.tipo, resposta.viagem);

                $("#btn_minhas_viagens").trigger('click');
            },

            beforeSend: function(){
                $('#loader').show();
            }

        });
    });

	$(document).ready(function(){
        $('#myModal').modal('hide');
		$("#data").datepicker({ minDate: 0});	
		$('#hora').timepicker();	
		$("#nav-tabs").button();
		$("#data").mask("99/99/9999");
		$("#hora").mask("99:99");
        $("#btn_amigos_viagens").trigger('click');
	});
});