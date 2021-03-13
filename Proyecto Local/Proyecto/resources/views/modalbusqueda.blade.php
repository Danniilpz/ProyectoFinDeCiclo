<div class="modal fade" id="modalConf" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              @php
              if(\Request::route()->getName() == 'home'||\Request::route()->getName() == 'images'){
                if(!Auth::check()){
                  if(Cookie::get('detalles') !== null && Cookie::get('detalles') == "on"){
                      $check1="checked";
                  }
                  if(Cookie::get('vistaprevia') !== null && Cookie::get('vistaprevia') == "on"){
                      $check2="checked";
                  }
                }
              }
              @endphp
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="detalles" class="custom-control-input" id="switch1" {{$check1}}>
                    <label class="custom-control-label" for="switch1">Búsqueda detallada</label>
                    <button type="button" class="interrogacion" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="Si marcas búsqueda detallada, el motor de búsqueda hará una busqueda en la red de palabras iguales y similares a las que buscas. Esto puede reducir la velocidad de búsqueda."></button>
                </div>
                @if (\Request::route()->getName() == 'home'||\Request::route()->getName() == 'busqueda')
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="vistaprevia" class="custom-control-input" id="switch2" {{$check2}}>
                        <label class="custom-control-label" for="switch2">Vistas previas</label>
                        <button type="button" class="interrogacion" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="Si marcas vistas previas, el motor de búsqueda mostrará un pequeño párrafo debajo de cada resultado. Esto puede reducir la velocidad de búsqueda."></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
