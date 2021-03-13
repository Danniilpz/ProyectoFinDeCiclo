<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EliminarCuentaController extends Controller
{
    function eliminar(Request $request){
        if(Auth::check()){
            if(!$request->has('confirm')||$request->input('confirm')!="true"){
                return view('confirmareliminacion');
            }
            else{
                DB::table('historial')->where('usuario', Auth::user()->id)->update(['usuario' => '0']);
                $this->rmdir_rf(base_path()."/users/images/".Auth::user()->id);
                unlink(base_path()."/users/config/".Auth::user()->id.".dat");
                unlink(base_path()."/users/config/".Auth::user()->id."_fondos.dat");
                Log::info('Usuario '.Auth::user()->id.' eliminó su cuenta.');
                DB::table('users')->where('id', Auth::user()->id)->delete();
                return redirect("/");
            }
        }
        else{
            return redirect("/login");
        }
    }
    function rmdir_rf($carpeta) //funcion que elimina un directorio y su contenido
    {
        foreach(glob($carpeta . "/*") as $archivos_carpeta){
            if (is_dir($archivos_carpeta)){
                $this->rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }
}
