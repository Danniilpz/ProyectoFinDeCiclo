@extends('layouts.secondary')

@section('pageTitle','Política de cookies - Loopz')

@section('hojasCSS')
    <link rel="stylesheet" href="css/forms.css"/>
@endsection


@section('content')
    <div class="container">
      <h2>Acerca de Loopz</h2>
      <hr/>
      <div class="row">
          <div class="form-group col-md-6 col-12 card">
              <div class="card-header">
                  <h4 class="text-center">Sobre nosotros</h4>
              </div>
              <div class="card-body">
                  <p>Loopz S.A es una empresa dedicada a la organización de la información en la red.</p>
                  <p>Nuestro objetivo es que toda la información almacenada en la gran nube que es Internet
                    esté organizada e indexada como en una biblioteca online; y ofrecer a los usuarios un
                    buscador que les acerque a esa información con solo unas letras o palabras clave.</p>
                  <p>La primera tarea es llevada a cabo por nuestro robot de indexación, un potente programa
                    que viaja a traves de la telaraña de la red en busca de información para almacenar en
                    nuestros índices.</p>
                  <p>La segunda la lleva a cabo nuestro motor de búsqueda, quien, al recibir las palabras
                    enviadas por los usuarios, en unos pocos segundos realiza una búsqueda en nuestros
                    índices de direcciones que devuelve a los usuarios ordenadas por prioridad de importancia.</p>
              </div>
          </div>
          <div class="form-group col-md-6 col-12 card">
              <div class="card-header">
                  <h4 class="text-center">Contacto</h4>
              </div>
              <div class="card-body">
                  <p>Nuestra sede se encuentra en Madrid, en la siguiente dirección:</p>
                  <p>
                    Paseo de la Castellana 62,<br/>
                    28003 Madrid,<br/>
                    España
                  </p>
                  <p>Puedes contactarnos por email o por teléfono:</p>
                  <p>
                    Información: info@loopz.cf<br/>
                    Director: daniel.lopez@loopz.cf<br/>
                    Teléfono: 910 23 21 53<br/>
                    Horario: 9:00 - 18:00<br/>
                  </p>
              </div>
          </div>
      </div>
  </div>
@endsection
