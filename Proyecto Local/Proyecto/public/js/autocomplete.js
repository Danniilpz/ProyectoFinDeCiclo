$(document).ready(function(){
    $('#buscador').on('keyup', function() {
        if($(this).val().trim()!=""){
            var partes=$(this).val().split(" ");
            var key = partes[partes.length-1];
            var dataString;
            if(partes.length>1){
                dataString = 'key='+key+"&compuesta=true";
            }
            else{
                dataString = 'key='+key;
            }
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: dataString,
                success: function(data) {
                    //Escribimos las sugerencias que nos manda la consulta
                    $('#suggestions').show().html(data);
                    //Al hacer click en algua de las sugerencias
                    $('.suggest-element').on('click', function(){
                        //Obtenemos la id unica de la sugerencia pulsada
                        var id = $(this).attr('id');
                        var palabra;
                        if(partes.length>1){
                            palabra=$('#'+id).html().substring(4);
                        }
                        else{
                            palabra=$('#'+id).html();
                        }
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#buscador').val("");
                        for(i=0;i<partes.length-1;i++){
                            $('#buscador').val($('#buscador').val()+partes[i]+" ");
                        }
                        $('#buscador').val( $('#buscador').val()+palabra);
                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestions').hide();
                        $("#botonsubmit input").click();
                        return false;
                    });
                }
            });
        }
        else{
            $('#suggestions').hide();
        }
    });
})