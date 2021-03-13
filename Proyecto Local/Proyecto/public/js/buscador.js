$(document).ready(function(){ //funcion para activar el modal
  $(function () {
      $('[data-toggle="popover"]').popover()
  });
  $('.popover-dismiss').popover({
      trigger: 'focus'
  });
  $("#botonsubmit").click(function(e){ //funcion para hacer aparece el "spinner"
      if($("#buscador").val().trim()==""){
          e.preventDefault();
      }
      else{
          $("#cargando").removeClass("displaynone");
          $("#submitlabel").addClass("displaynone");
          $("#iconbuscador").addClass("displaynone");
      }
  });
});
