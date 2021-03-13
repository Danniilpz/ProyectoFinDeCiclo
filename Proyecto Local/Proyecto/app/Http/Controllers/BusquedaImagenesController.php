<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Custom\Imagen;
use App\Custom\Funciones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class BusquedaImagenesController extends Controller
{
  public function busca(Request $request){
    if(!$request->has('search')||trim($request->input('search'))==""){
      return redirect('/');
    }
    else{
      $functions=new Funciones(); //clase propia con funciones útiles
      $search=$request->input('search');
      $detalles=false;
      $partes_search=explode(" ",$search); //divide la busqueda en las diferentes palabras
      $functions->guardaHistorial($search,"imagen");
      if($request->has("detalles")&&$request->input('detalles')=="on"){
          $detalles=true;
          $operacion="LIKE";
          $patron="%";
      }
      else{
          $operacion="=";
          $patron="";
      }
      $where_condition=""; //variable que contendrá las clausulas where de cada palabra de la busqueda, en caso de haber más de una
      for($i=1;$i<count($partes_search);$i++){
        $where_condition.="direccion IN (select `direccion` from `keywords` where `keyword` $operacion '$patron".$partes_search[$i]."$patron')"; //subconsultas para la busqueda compuesta
        if($i<count($partes_search)-1){
          $where_condition.=" AND ";
        }
      }

      $resultados=array(); //array que almacenarar los resultados para pasar a la vista

      if(count($partes_search)>1){
        $direcciones=DB::table('keywords')
                            ->orderBy('prioridad','desc')
                            ->where('keyword',$operacion,$patron.$functions->eliminar_tildes($partes_search[0]).$patron)
                            ->whereRaw($where_condition)
                            ->pluck('direccion')
                            ->toArray(); //hago la consulta principal con todas las palabras
        $consultas=array();
        for($i=0;$i<count($partes_search);$i++){ //hago consultas por cada palabra y las guardo en un array
          $consultas[]=DB::table('keywords')
                              ->orderBy('prioridad','desc')
                              ->where('keyword',$operacion,$patron.$functions->eliminar_tildes($partes_search[0]).$patron)

                              ->pluck('direccion')
                              ->toArray();
        }
        for($i=0;$i<count($consultas);$i++){
          $direcciones=array_merge($direcciones,$consultas[$i]); //junto todos los resultados
        }
      }
      else {
        $direcciones=DB::table('keywords')
                            ->orderBy('prioridad','desc')
                            ->where('keyword',$operacion,$patron.$functions->eliminar_tildes($partes_search[0]).$patron)

                            ->pluck('direccion')
                            ->toArray();
      }
      $direcciones=array_unique($direcciones);

      $imagenes=array();
      $resultados=array();
      foreach ($direcciones as $enlace) {
          $listaimagenes=DB::table('imagenes')
                          ->where('direccion',$enlace)
                          ->pluck('imagen','direccion')
                          ->toArray();
          $imagenes=array_merge($imagenes,$listaimagenes);
      }
      foreach ($imagenes as $direccion=>$imagen){
          $resultados[]=new Imagen($imagen,$direccion);
      }
      $numres=count($resultados); //numero total de resultados
        $numpaginas=$numres/12;
        $pags=explode(".",$numpaginas);
        if(count($pags)>1){
          $numpaginas=$pags[0]+1;
        }
        if(!$request->has('page')||$request->input('page')==1){
          $skip=0;
          $pagina=1;
        }
        else if($request->has('page')&&$request->input('page')<=$numpaginas){
          $pagina=$request->input('page');
          $skip=($pagina-1)*12;
        }
        else{
          return redirect('/');
        }

        $resultados=array_slice($resultados,$skip,12); //obtengo solo 12 resultados

      if(Auth::check()) {
          if (!file_exists(base_path()."/users/config/" . Auth::id() . ".dat")) {
              $check1 = "";
              $check2 = "";
          } else {
              $f = fopen(base_path()."/users/config/" . Auth::id() . ".dat", "r");
              $preferencias = fgets($f);
              fclose($f);
              if (substr($preferencias, 0, 1) == "1") {
                  $check1 = "checked";
              } else {
                  $check1 = "";
              }
              if (substr($preferencias, 1, 1) == "1") {
                  $check2 = "checked";
              } else {
                  $check2 = "";
              }
          }
      }
      else{
          if($request->has("detalles")&&$request->input('detalles')=="on"){
            $check1 = "checked";
          }
          else{
            $check1 = "";
          }
          if($request->has("vistaprevia")&&$request->input('vistaprevia')=="on"){
            $check2 = "checked";
          }
          else{
            $check2 = "";
          }
      }
      $response=new Response(view("resultadosimagenes",[
        'numres'=>$numres,
        'resultados'=>$resultados,
        'search'=>$search,
        'paginas'=>$numpaginas,
        'actual'=>$pagina,
        'detalles'=>$detalles,
        'check1'=>$check1,
        'check2'=>$check2,
      ]));
      if(Auth::check()) {
          if($request->hasCookie("detalles")){
            $response=$response->withCookie(Cookie::forget('detalles'));
          }
          if($request->hasCookie("vistaprevia")){
            $response=$response->withCookie(Cookie::forget('vistaprevia'));
          }
      }
      else{
          if($request->has("detalles")&&$request->input('detalles')=="on"){
            if(!$request->hasCookie("detalles")){
              $response=$response->withCookie(cookie('detalles', 'on', 60));
            }
          }
          else{
            if($request->hasCookie("detalles")){
              $response=$response->withCookie(Cookie::forget('detalles'));
            }
          }
          if($request->has("vistaprevia")&&$request->input('vistaprevia')=="on"){
            if(!$request->hasCookie("vistaprevia")){
              $response=$response->withCookie(cookie('vistaprevia', 'on', 60));
            }
          }
          else{
            if($request->hasCookie("vistaprevia")){
              $response=$response->withCookie(Cookie::forget('vistaprevia'));
            }
          }
      }
      return $response;
    }
  }

}
