<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class CambiaPreferenciasController extends Controller
{
    function cambiarPreferencias(Request $request){
        if(Auth::check()){
            if(!file_exists(base_path()."/users/config/".Auth::id().".dat")){
                $preferencias="";
                if($request->has('detalles')){
                    $preferencias.="1";
                }
                else{
                    $preferencias.="0";
                }
                if($request->has('vistaprevia')){
                    $preferencias.="1";
                }
                else{
                    $preferencias.="0";
                }
                $preferencias.="1";
            }
            else{
                $f=fopen(base_path()."/users/config/".Auth::id().".dat","r");
                $preferencias2=fgets($f);
                fclose($f);
                $preferencias="";
                if($request->has('detalles')){
                    $preferencias.="1";
                }
                else{
                    $preferencias.="0";
                }
                if($request->has('vistaprevia')){
                    $preferencias.="1";
                }
                else{
                    $preferencias.="0";
                }
                $preferencias.=substr($preferencias2,2,1);
            }
            $f=fopen(base_path()."/users/config/".Auth::id().".dat","w");
            flock($f,2);
            fwrite($f,$preferencias);
            flock($f,3);
            fclose($f);
            Session::flash('success', 'Se han modificado tus preferencias.');
            return redirect()->route('settings');
        }
        else{
            return redirect('/login');
        }
    }
}
