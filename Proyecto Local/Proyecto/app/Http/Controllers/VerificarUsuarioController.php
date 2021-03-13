<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;

class VerificarUsuarioController extends Controller
{
    function verificar(Request $request){
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
                $id=(DB::table('users')->where('id', $request->input('user'))->pluck('id'));
                if($id){
                    $id=$id[0];
                    DB::table('users')->where('id', $id)->update(['email_verified_at' => DB::raw('NOW()')]);
                    Log::info('Administrador verificó la cuenta de '.$id);
                    Session::flash('success', 'Se ha verificado el usuario con éxito.');
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
}
