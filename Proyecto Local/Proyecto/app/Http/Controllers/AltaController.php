<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Custom\Funciones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AltaController extends Controller
{
  function alta(Request $request){
      if(Auth::check()){
          $functions=new Funciones();
          if(!$request->has('direccion')||trim($request->input('direccion'))==""){
              return view("alta");
          }
          else if(!filter_var($request->input('direccion'), FILTER_VALIDATE_URL)){
              return view("alta",array('error'=>'Introduce una URL válida'));
          }
          else{
              $url=$request->input('direccion');
              $url=parse_url($url,PHP_URL_SCHEME)."://".parse_url($url,PHP_URL_HOST)."/";
              if(!$functions->url_ya_existente($url)){//si no está ya registrada
                  $now = new \DateTime();
                  DB::table('sitios')->insert(
                      ['direccion' => $url,'fecha_exp' => $now->format('Y-m-d H:i:s'),'bloqueado'=>0]
                  );
                  return view("alta",array('success'=>'¡Se ha dado de alta el sitio web con éxito!'));
              }
              else{
                  return view("alta",array('error'=>'El sitio web ya se encuentra dado de alta'));
              }
          }
      }
      else{
          return redirect("login");
      }
  }
}
