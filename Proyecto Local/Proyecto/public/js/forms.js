$(document).ready(function(){
    if($("#fondoactivo").prop('checked')){
        $("#fondo").prop( "disabled", false );
        $(".fondocheck").prop( "disabled", false );
    }
    else{
        $("#fondo").prop( "disabled", true );
        $(".fondocheck").prop( "disabled", true );
    }
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $("#fondoactivo").on('change',function(){
        if($(this).prop('checked')){
            $("#fondo").prop( "disabled", false );
            $(".fondocheck").prop( "disabled", false );
        }
        else{
            $("#fondo").prop( "disabled", true );
            $(".fondocheck").prop( "disabled", true );
        }
    });
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
    $('.popover-dismiss').popover({
        trigger: 'focus'
    });
    $('.fondocheck').on('change',function(){
        if(!$(this).prop('checked')){
            if($('.fondocheck:checked').length==0){
                $(this).prop('checked',true);
            }
        }
    })
})
