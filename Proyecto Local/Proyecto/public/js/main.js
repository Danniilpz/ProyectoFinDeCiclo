$(document).ready(function(){
    $(function () {
        $('[data-toggle="popover"]').popover()
    });
    $("#input").focusin(function () {
        if($("#carousel").is(':visible')) {
            $("#fondo").fadeIn();
        }
    });
    $("#input").focusout(function () {
        if($("#carousel").is(':visible')) {
            $("#fondo").fadeOut();
        }
    });
    $("#suggestions").focusin(function () {
        $("#suggestions").show();
    });
    $("[data-toggle=popover]").popover({
        html: true,
        content: function() {
            return $('#popover-content').html();
        }
    });
    $("#tema").click(function(){
        if(!$("#carousel").is(':visible')){
            $("#carousel").show();
            $(".nav-link").addClass("sombras");
            $(".navbar").addClass("navbar-dark");
            $(".navbar").removeClass("navbar-light");
            $("#footer a").addClass("sombras");
            $("#footer a").addClass("text-light");
            $("#footer a").removeClass("text-dark");
            $('.input-group').removeClass("withborders");
            $('.input-group').animate({ top: '50px' });
            $('.input-group').animate({ bottom: '100px' });
            $('#tema img').attr('src','images/tema.png');
            cambiartema(true);
        }
        else{
            $("#carousel").hide();
            $(".nav-link").removeClass("sombras");
            $(".navbar").removeClass("navbar-dark");
            $(".navbar").addClass("navbar-light");
            $("#footer a").removeClass("sombras");
            $("#footer a").removeClass("text-light");
            $("#footer a").addClass("text-dark");
            $('.input-group').addClass("withborders");
            $('.input-group').animate({ top: '100px' });
            $('.input-group').animate({ bottom: '0px' });
            $('#tema img').attr('src','images/tema2.png');
            cambiartema(false);
        }

    });
})
function cambiartema(visible){
    var dataString;
    if(visible){
        dataString="fondoactivo=on";
    }
    else{
        dataString=""
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "cambiartema",
        data: dataString,
        success: function(data) {

        }
    });
}
