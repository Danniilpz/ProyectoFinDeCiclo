<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;

class EliminarUsuarioController extends Controller
{
    function eliminar(Request $request){
        if(Auth::check()&&Auth::user()->id==1){
            if(!$request->has('user')||trim($request->input('user'))==""){
                Session::flash('error', 'Selecciona un usuario válido.');
                return redirect("/listadousuarios");
            }
            else if(!is_numeric($request->input('user'))){
                Session::flash('error', 'Selecciona un usuario válido.');
                return redirect("/listadousuarios");
            }
            else{
                if($request->input('user')==Auth::user()->id){
                    Session::flash('error', 'No es posible eliminar ese usuario.');
                    return redirect("/listadousuarios");
                }
                $id=(DB::table('users')->where('id', $request->input('user'))->pluck('id'));
                if($id){
                    $id=$id[0];
                    DB::table('historial')->where('usuario', $id)->update(['usuario' => '0']);
                    if(file_exists(base_path()."/users/images/".$id)){
                        $this->rmdir_rf(base_path()."/users/images/".$id);
                    }
                    if(file_exists(base_path()."/users/config/".$id.".dat")){
                        $this->rmdir_rf(base_path()."/users/config/".$id.".dat");
                    }
                    if(file_exists(base_path()."/users/config/".$id."_fondos.dat")){
                        $this->rmdir_rf(base_path()."/users/config/".$id."_fondos.dat");
                    }
                    DB::table('users')->where('id', $id)->delete();
                    Log::info('Administrador eliminó la cuenta de '.$id);
                    Session::flash('success', 'Se ha borrado el usuario con éxito.');
                }
                else{
                    Session::flash('error', 'El usuario no existe.');
                }
                return redirect("/listadousuarios");
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
