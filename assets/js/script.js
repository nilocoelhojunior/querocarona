function removermodal(){
    $('.fundo_transparente').css({display: "none"});
    $('#exp_modal').css({display: "none"});  
    $('#cabecalho_modal').remove();
    $('#detalhe_modal').remove();
    $('#confirmados_modal').remove();
    $('#solicitacoes_modal').remove();
    $('#passageiros_modal').remove();
    $('#recusa_solicitacao').remove();
    $('#confirma_solicitacao').remove();
    
    $('#obs_modal').remove();
    $('#confima_modal').remove();
    $('#close_modal').remove();
    $('#excluir_modal').remove();
}

function criamodal(id_viagem){
    var objcss = {
        "display": "block",
        "position": "absolute",
        "margin-top": "-535px",
        "margin-left": "0px",
        "background-color": "black",
        "opacity": "0.6",
        "width": $(".canvas").width(),
        "height": $(".canvas").height()
    }

    $('.fundo_transparente').css(objcss);
    $('#exp_modal').css({display: "block"});
    $('#btn_modal').append('<div id="confima_modal" onclick="participarcarona('+id_viagem+');"></div>');
    $('#btn_modal').append('<div id="close_modal" onclick="naoparticiparcarona();"></div>');    
}

function naoparticiparcarona(){
    removermodal();
}

function participarcarona(id_viagem){
    removermodal();

    iurl = 'principal/participarcarona/'+id_viagem;

    $.ajax({

        type: "GET",

        url: iurl,

        dataType: "html",

        success: function(resposta){
            $('#loader').css({display:"none"});  
            $('#form').append(resposta);
            $('#info').fadeIn(300).delay(3000).slideUp(400);
        },

        beforeSend: function(){
            $('#loader').css({display:"block"});            
        },

        error: function(){

        }
    });
}

function excluirviagem(id_viagem){
    removermodal();

    iurl = 'principal/excluirviagem/'+id_viagem;

    $.ajax({

        type: "GET",

        url: iurl,

        dataType: "html",

        success: function(resposta){
            $('#loader').css({display:"none"});  
            $('#form').append(resposta);
            $('#info').fadeIn(300).delay(3000).slideUp(400);
        },

        beforeSend: function(){
            $('#loader').css({display:"block"});            
        },

        error: function(){

        }
    });
}

function modal_viagens(id_viagem){

    criamodal(id_viagem);

    $('#confima_modal').css("margin-left", "459px");

    var viagemurl = 'principal/buscaviagem/'+id_viagem;

    $.ajax({
        
        type: "GET",

        url: viagemurl,

        dataType: "json",

        success: function(resposta){
            $('#modal_loader').css({display:"none"});

            var user_name;
            var url = 'http://graph.facebook.com/'+resposta.viagem[0].id_usuario;
            var carona_url = 'principal/buscacarona/'+id_viagem;

            $.getJSON(url, function(dados){
                user_name = dados.name;
                $.each(resposta, function(x, valor){
                    $.each(valor, function (n, nome){
                        var ano = nome.data.substring(0, 4);
                        var dia = nome.data.substring(5, 7);
                        var mes = nome.data.substring(8, 10);
                        var hora = nome.hora.substring(0, 5);
                        var tipo;
                        if (nome.solicitante == 0){
                            tipo = "Ofertou";
                        }else if(nome.solicitante ==1){
                            tipo = "Solicitou";
                        }
                        $('#cabecalho_modal').append('<img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; padding-left: 5px; margin-top: 2px;"/><br><b>'+user_name+'</b>');
                        $('#detalhe_modal').append('<p><br>'+tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+dia+'/'+mes+'/'+ano+'</b> às <b>'+hora+'</b>.</p>');
                        $('#obs_modal').append('<b>Obs</b><p>'+nome.obs+'</p>');
                    });
                });
            });
            
            $.getJSON(carona_url, function(dados){
                $.each(dados, function(x, valor){
                    $.each(valor, function(n, nome){
                        url = 'http://graph.facebook.com/'+nome.id_usuario;
                        $.getJSON(url, function(teste){
                            user_name = teste.name;
                            $('#passageiros_modal').append('<p title="'+user_name+'"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; padding-left: 5px; margin-top: 2px;"/></p>');   
                        });
                    });
                });
            });
        },

        beforeSend: function(){
            $('#modal_loader').css({display:"block"});
        },

        error: function(){

        }
    })
}

function modal_minhas_viagens(id_viagem){
    criamodal(id_viagem);
    $('#btn_modal').append('<div id="excluir_modal" onclick="excluirviagem('+id_viagem+');"></div>');

    var viagemurl = 'principal/buscaviagem/'+id_viagem;

    $.ajax({
        
        type: "GET",

        url: viagemurl,

        dataType: "json",

        success: function(resposta){
            $('#modal_loader').css({display:"none"});

            var user_name;
            var url = 'http://graph.facebook.com/'+resposta.viagem[0].id_usuario;
            var carona_url = 'principal/buscacarona/'+id_viagem;

            $.getJSON(url, function(dados){
                user_name = dados.name;
                $.each(resposta, function(x, valor){
                    $.each(valor, function (n, nome){
                        var ano = nome.data.substring(0, 4);
                        var dia = nome.data.substring(5, 7);
                        var mes = nome.data.substring(8, 10);
                        var hora = nome.hora.substring(0, 5);
                        var tipo;
                        if (nome.solicitante == 0){
                            tipo = "Ofertou";
                        }else if(nome.solicitante ==1){
                            tipo = "Solicitou";
                        }
                       $('#conteudo_modal').append('<div id="cabecalho_modal"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; padding-left: 5px; margin-top: 2px;"/><br><b>'+user_name+'</b></div><div id="detalhe_modal"><br><p>'+tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+dia+'/'+mes+'/'+ano+'</b> às <b>'+hora+'</b>.</p></div>');

                       if (nome.obs.length >= 0){
                        $('#conteudo_modal').append('<div id="obs_modal"><b>Obs</b><p>'+nome.obs+'</p></div>');
                       }else{
                        $('#conteudo_modal').append('<div id="obs_modal"><b>Obs</b></div>');
                       }
                    });
                });
            });

            $.getJSON(carona_url, function(dados){
                $('#conteudo_modal').append('<div title="Usuarios confirmados com carona confirmada." id="passageiros_modal"><b>Passageiros</b></div>');
                $('#conteudo_modal').append('<div title="Solicitações recentes de suas viagens"id="solicitacoes_modal"><b>Solicitadas</b></div>');
                $.each(dados, function(x, valor){
                    $.each(valor, function(n, nome){
                        url = 'http://graph.facebook.com/'+nome.id_usuario;
                        $.getJSON(url, function(teste){
                            user_name = teste.name;
                            if (nome.confirmada == 1){
                                $('#passageiros_modal').append('<p title="'+user_name+'"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; padding-left: 5px; margin-top: 8px;"/></p>');   
                            }else if(nome.confirmada == 0){
                                $('#solicitacoes_modal').append('<div title="Confirmar" id="confirma_solicitacao"></div><div title="Recusar" id="recusa_solicitacao"></div><div style="margin-top: -56px; margin-left: 24px;" title="'+user_name+'"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="45" height="45" style="float:left; padding-right: 5px; padding-left: 5px; margin-top: 8px;"/></div>');   
                            }
                        });
                    });
                });
            });
        },

        beforeSend: function(){
            $('#modal_loader').css({display:"block"});
        },

        error: function(){

        }
    }) 
}

$(function(){

	$("#chk_ofertar").click(function(){
    
        $.ajax({

            type: "POST",

            url: "principal/ofertar",

            datatype: "json",

            data: {origem: $('input:text[name=origem]').val(), destino: $('input:text[name=destino]').val(), data: $('input:text[name=data]').val(), hora: $('input:text[name=hora]').val(), obs: $('#obs').val()},

            success: function(resposta){
            	$('#loader').css({display:"none"});  
                $('#form').append(resposta);
                $('#info').fadeIn(300).delay(3000).slideUp(400);
            },

            beforeSend: function(){
                $('#loader').css({display:"block"});
            },

            error: function(){
                $("#infoerror").fadeIn(300).delay(2000).slideUp(400);
            }
        });
        
        $('#formulario').get(0).reset();
    });
    

    $("#chk_solicitar").click(function(){

        $.ajax({

            type: "POST",

            url: "principal/solicitar",

            datatype: "html",

            data: {origem: $('input:text[name=origem]').val(), destino: $('input:text[name=destino]').val(), data: $('input:text[name=data]').val(), hora: $('input:text[name=hora]').val(), obs: $('#obs').val()},

            success: function(resposta){
                $('#loader').css({display:"none"});  
                $('#form').append(resposta);
                $('#info').fadeIn(300).delay(3000).slideUp(400);
            },

            beforeSend: function(){
                $('#loader').css({display:"block"});
            },

            error: function(){
                $("#infoerror").fadeIn(300).delay(2000).slideUp(400);
            }
        });
        
        $('#formulario').get(0).reset();
    });


    $("#btn_minhas_viagens").click(function(){

        $("p").remove();

        jQuery.error = console.error;

        $(".btn_minhas_viagens").switchClass( "btn_minhas_viagens", "btn_minhas_viagens_ativa", 1);

        if($("#btn_amigos_viagens").hasClass('btn_amigos_viagens_ativa')){
            $( ".btn_amigos_viagens_ativa" ).switchClass( "btn_amigos_viagens_ativa", "btn_amigos_viagens", 1);
        }        

        $.ajax({

            type: "GET",

            url: "principal/minhasviagens",

            dataType: "json",

            success: function(resposta){ 
                $('#loader_viagem').css({display:"none"});

                var user_name;
                var url = 'http://graph.facebook.com/'+resposta.viagem[0].id_usuario;

                $.getJSON(url, function(data){
                    user_name = data.name;
                    $.each(resposta, function(i, valor){
                        $.each(valor, function(n, nome){
                                var ano = nome.data.substring(0, 4);
                                var dia = nome.data.substring(5, 7);
                                var mes = nome.data.substring(8, 10);
                                var hora = nome.hora.substring(0, 5);
                                var tipo;

                                if (nome.solicitante == 0){
                                    tipo = "ofertou";
                                }else if(nome.solicitante ==1){
                                    tipo = "solicitou";
                                }

                                var obj = {}

                               $('.viagem').append('<p onclick="modal_minhas_viagens('+nome.id_viagem+');"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/><b>'+user_name+'</b> '+tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+dia+'/'+mes+'/'+ano+'</b> às <b>'+hora+'</b>.</p>');
                        });
                    });            
                });
            },

            beforeSend: function(){
                $('#loader_viagem').css({display:"block"});
            },

            error: function(){

            }
        });
    });
    

    $("#btn_amigos_viagens").click(function(){

        $("p").remove();

        jQuery.error = console.error;

        $(".btn_amigos_viagens").switchClass( "btn_amigos_viagens", "btn_amigos_viagens_ativa", 1);

        if($("#btn_minhas_viagens").hasClass('btn_minhas_viagens_ativa')){            
            $( ".btn_minhas_viagens_ativa" ).switchClass( "btn_minhas_viagens_ativa", "btn_minhas_viagens", 1);
        }      

        $.ajax({

            type: "GET",

            url: "principal/amigosviagens/",

            dataType: "json",

            success: function(resposta){ 
                $('#loader_viagem').css({display:"none"});
                if (resposta.length == 0){
                    $('.viagem').append('<p style="position: absolute; margin-top: 20px; margin-left: 20px; font-size: 16px; cursor: auto; background-color: transparent;">Seus amigos não possuem viagens.</p>');
                }else{
                    $.each(resposta, function(i, valor){
                        $.each(valor, function(n, nome){
                            var user_name;
                            var url = 'http://graph.facebook.com/'+nome.id_usuario;

                            $.getJSON(url, function(data){
                                user_name = data.name;
                                                            
                                var ano = nome.data.substring(0, 4);
                                var mes = nome.data.substring(5, 7);
                                var dia = nome.data.substring(8, 10);
                                var hora = nome.hora.substring(0, 5);
                                var tipo;
                                
                                if (nome.solicitante == 0){
                                    tipo = "ofertou";
                                }else if(nome.solicitante ==1){
                                    tipo = "solicitou";
                                }

                               $('.viagem').append('<p onclick="modal_viagens('+nome.id_viagem+');"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/><b>'+user_name+'</b> '+tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+dia+'/'+mes+'/'+ano+'</b> às <b>'+hora+'</b>.</p>');
                           });
                        });            
                    });
                }
            },

            beforeSend: function(){
                $('#loader_viagem').css({display:"block"});
            },

            error: function(){

            }
        });
    });

	$(document).ready(function(){
		$("#data").datepicker({ minDate: 0});	
		$('#hora').timepicker();	
		$("#nav-tabs").button();
		$("#data").mask("99/99/9999");
		$("#hora").mask("99:99");
        $("#btn_amigos_viagens").trigger('click');
	});
});