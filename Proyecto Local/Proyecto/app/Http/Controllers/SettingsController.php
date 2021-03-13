<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\Funciones;

class SettingsController extends Controller
{
    function cargaConfiguracion(Request $request){
        $functions=new Funciones(); //clase propia con funciones útiles
        if(Auth::check()){
            if(!file_exists(base_path()."/users/config/".Auth::id().".dat")){
                $check1="";
                $check2="";
                $check3="checked";
            }
            else{
                $f=fopen(base_path()."/users/config/".Auth::id().".dat","r");
                $preferencias=fgets($f);
                fclose($f);
                if(substr($preferencias,0,1)=="1"){
                    $check1="checked";
                }
                else{
                    $check1="";
                }
                if(substr($preferencias,1,1)=="1"){
                    $check2="checked";
                }
                else{
                    $check2="";
                }
                if(substr($preferencias,2,1)=="1"){
                    $check3="checked";
                }
                else{
                    $check3="";
                }
            }
            $dir_images=base_path()."/users/images/".Auth::id()."/";
            if(!file_exists($dir_images)){
                $num_images=0;
            }
            else{
                $num_images=$functions->countImages($dir_images);
            }
            if(!file_exists(base_path()."/users/config/".Auth::id()."_fondos.dat")){
                $fondosactivos="123";
            }
            else{
                $f=fopen(base_path()."/users/config/".Auth::id()."_fondos.dat","r");
                $fondosactivos=fgets($f);
                fclose($f);
            }
        }
        else{
            if($request->route()->getName()=="home"||$request->route()->getName()=="images"){
                $check1="";
                $check2="";
                $check3="checked";
                $num_images=3;
                $fondosactivos="123";
            }
            else{
                return redirect("/login");
            }
        }
        if($request->route()->getName()=="home"||$request->route()->getName()=="images"){
            $view="home";
        }
        else{
            $view="settings";
        }
        return view($view,[
            'check1'=>$check1,
            'check2'=>$check2,
            'check3'=>$check3,
            'num_images'=>$num_images,
            'fondos_activos'=>$fondosactivos,
        ]);
    }
}
