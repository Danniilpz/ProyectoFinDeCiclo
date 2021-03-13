<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\Funciones;
use Session;

class CambiaTemaController extends Controller
{
    function cambiarTema(Request $request){
        $functions=new Funciones(); //clase propia con funciones útiles
        if(Auth::check()){
            if($request->has('fondoactivo')){
                if(!file_exists(base_path()."/users/config/".Auth::id().".dat")){
                    $preferencias="001";
                }
                else{
                    $f=fopen(base_path()."/users/config/".Auth::id().".dat","r");
                    $preferencias2=fgets($f);
                    fclose($f);
                    $preferencias="";
                    $preferencias.=substr($preferencias2,0,2);
                    $preferencias.="1";
                }
                $f=fopen(base_path()."/users/config/".Auth::id().".dat","w");
                flock($f,2);
                fwrite($f,$preferencias);
                flock($f,3);
                fclose($f);
                if (!$request->isXmlHttpRequest()) { //si no es peticion ajax
                    $dir_subida = base_path()."/users/images/" . Auth::id() . "/";
                    if (!file_exists($dir_subida)) {
                        mkdir($dir_subida);
                    }
                    $totalimages = $functions->countImages($dir_subida);
                    if ($_FILES['fondo']['name'] != "") {
                        if ($totalimages >= 5) {
                            Session::flash('error', 'No puedes subir más de 5 imágenes. Debes borrar alguna de las existentes');
                            return redirect('/settings');
                        } else if(getimagesize($_FILES['fondo']['tmp_name'])[2]==""){
                            Session::flash('error', 'Selecciona una imagen válida.');
                            return redirect('/settings');
                        } else {
                            $nombre=($totalimages+1).".jpg";
                            $dir_subida=base_path()."/users/images/".Auth::id()."/";
                            $fichero_subido = $dir_subida.$nombre;
                            if (!move_uploaded_file($_FILES['fondo']['tmp_name'], $fichero_subido)){
                                Session::flash('error', 'No se pudo subir tu imagen.');
                                return redirect('/settings');
                            }
                        }
                    }

                    $fondosactivados="";
                    for($i=0;$i<=($totalimages+3);$i++){ //el total + 3 es para contar con las imagenes por defecto
                        if($request->has('fondo'.$i)){
                            $fondosactivados.=$i;
                        }
                    }
                    if(strlen($fondosactivados)>0){
                        $f=fopen(base_path()."/users/config/".Auth::id()."_fondos.dat","w");
                        flock($f,2);
                        fwrite($f,$fondosactivados);
                        flock($f,3);
                        fclose($f);
                    }
                    else{
                        Session::flash('error', 'Debes seleccionar al menos un fondo. Si no deseas mostrar ninguno, desactiva "Mostrar fondos de pantalla ".');
                        return redirect('/settings');
                    }
                }
            }
            else{
                if(!file_exists(base_path()."/users/config/".Auth::id().".dat")){
                    $preferencias="000";
                }
                else{
                    $f=fopen(base_path()."/users/config/".Auth::id().".dat","r");
                    $preferencias2=fgets($f);
                    fclose($f);
                    $preferencias="";
                    $preferencias.=substr($preferencias2,0,2);
                    $preferencias.="0";
                }
                $f=fopen(base_path()."/users/config/".Auth::id().".dat","w");
                flock($f,2);
                fwrite($f,$preferencias);
                flock($f,3);
                fclose($f);
            }
            Session::flash('success', 'Se ha modificado tu tema.');
            return redirect('/settings');
        }
        else{
            return redirect('/login');
        }
    }
    function borrarImagen(Request $request){
        $functions=new Funciones(); //clase propia con funciones útiles
        if(Auth::check()){
            if($request->has('img')){
                $numero=$request->input('img');
                $dir=base_path()."/users/images/".Auth::id()."/";
                $total=$functions->countImages($dir);
                if($numero<=$total){
                    unlink($dir.$numero.".jpg");
                    for($i=$numero+1;$i<=$total;$i++){
                        rename($dir.$i.".jpg",$dir.($i-1).".jpg");
                    }
                    $fondos=file(base_path()."/users/config/".Auth::id()."_fondos.dat")[0];
                    if(strpos($fondos,strval($numero+3))!==false){
                      $pos=strpos($fondos,strval($numero+3));
                      $fondos2=substr($fondos,0,$pos);
                      for($i=$pos+1;$i<strlen($fondos);$i++){
                        $fondos2.=substr($fondos,$i,1)-1;
                      }
                    }
                    else{
                      $fondos2="";
                      for($i=0;$i<strlen($fondos);$i++){
                        if(substr($fondos,$i,1)<($numero+3)){
                          $num=substr($fondos,$i,1);
                          $fondos2.=$num;
                        }
                        else{
                          $num=substr($fondos,$i,1)-1;
                          $fondos2.=$num;
                        }
                      }
                    }
                    if($fondos2==""){
                      $fondos2="123";
                    }
                    $f=fopen(base_path()."/users/config/".Auth::id()."_fondos.dat","w");
                    flock($f,2);
                    fwrite($f,$fondos2);
                    flock($f,3);
                    fclose($f);
                    return redirect('/settings');
                }
                else{
                    Session::flash('error', 'La imagen que intentas borrar no existe.');
                    return redirect('/settings');
                }
            }
            else{
                Session::flash('error', 'La imagen que intentas borrar no existe.');
                return redirect('/settings');
            }
        }
        else{
            return redirect('/login');
        }

    }
}
