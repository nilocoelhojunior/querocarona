function exibeviagem(tipo){
    $("p").remove();
    $("span").remove();

    jQuery.error = console.error;

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
                $('#loader_viagem').css({display:"none"});

                if(resposta.tipo == 1){
                    $('.viagem').append('<span style="position: absolute; margin-left: 20px; margin-top: 20px; font-size: 16px;">'+resposta.viagem+'</span>');
                }else if(resposta.tipo == 2){
                    $.each(resposta, function(i, valor){
                        $.each(valor, function(n, nome){
                               $('.viagem').append('<p onclick="modalviagens('+nome.id_viagem+');"><img src="https://graph.facebook.com/'+nome.id_usuario+'/picture" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/><b>'+nome.nome+'</b> '+nome.tipo+' carona de <b>'+nome.origem+'</b> para <b>'+nome.destino+'</b> dia <b>'+nome.data+'</b> às <b>'+nome.hora+'</b>.</p>');
                        });
                    });            
                }
            },

            beforeSend: function(){
                $('#loader_viagem').css({display:"block"});
            }
    });
}

function modalviagens(tipo){

    $('h3').html('');
    $('img').remove();
    $('p').remove();

    iurl =  "principal/exibecarona/"+tipo;

    $.ajax({

            type: "GET",

            url: iurl,

            dataType: "json",

            success: function(resposta){ 

                $('#loader_viagem').css({display:"none"});

                $('.modal-header').append('<img src="https://graph.facebook.com/'+resposta.viagem[0].id_usuario+'/picture" width="35" height="35" style="float: left; padding-right: 5px; margin-left: -11px; margin-top: -31px;"/>');
                $('h3').html(resposta.viagem[0].nome);

                $('#modal-viagem').append('<p style="font-size: 13px;"><b>'+resposta.viagem[0].tipo_solicitacao+'</b> carona de <b>'+resposta.viagem[0].origem+'</b> para <b>'+resposta.viagem[0].destino+'</b> dia <b>'+resposta.viagem[0].data+'</b> às <b>'+resposta.viagem[0].hora+'</b>.</p>');
                $('#modal-viagem-exibe-obs').append('<p>'+resposta.viagem[0].obs+'</p>');
                $.each(resposta.carona, function(i, valor){
                    if(valor.confirmada == 1){
                        $('#modal-confirmados-detalhe').append('<img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/>');
                    }else if(valor.confirmada == 0){
                        $('#modal-solicitados-detalhe').append('<img src="https://graph.facebook.com/'+valor.id_usuario+'/picture" title="'+valor.nome+'" width="30" height="30" style="float:left; padding-right: 5px; margin-top: 2px;"/>');
                    }

                });            
            },

            beforeSend: function(){
                $('#loader_viagem').css({display:"block"});
            }
    });

    $('#myModal').modal('show');
    $("#btn_minhas_viagens").trigger('click');
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

            datatype: "json",

            data: {origem: $('input:text[name=origem]').val(), destino: $('input:text[name=destino]').val(), data: $('input:text[name=data]').val(), hora: $('input:text[name=hora]').val(), obs: $('#obs').val()},

            success: function(resposta){
                $('#loader').css({display:"none"});  
                $('#form').append(resposta);
                $('#info').fadeIn(300).delay(3000).slideUp(400);
                $("#btn_minhas_viagens").trigger('click');
            },

            beforeSend: function(){
                $('#loader').css({display:"block"});
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