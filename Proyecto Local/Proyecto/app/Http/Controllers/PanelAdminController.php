<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelAdminController extends Controller
{
    function cargapanel(Request $request){
        if(Auth::check()&&Auth::user()->id==1){
            $busquedas=DB::table('historial')->select(DB::raw('count(busqueda) as veces, busqueda'))->groupBy('busqueda')->orderBy('veces','desc')->get();
            $datos=array();
            foreach ($busquedas as $busqueda){
                $datos[]=[$busqueda->busqueda,$busqueda->veces];
            }
            if(count($datos)>10){
                $datos=array_slice($datos,0,10);
            }
            $keywords=DB::table('keywords')->select(DB::raw('count(distinct keyword) as contadorkeywords'))->get()->first();
            $direcciones=DB::table('direcciones')->select(DB::raw('count(direccion) as contadordirecciones'))->get()->first();
            $sitios=DB::table('sitios')->select(DB::raw('count(direccion) as contadorsitios'))->get()->first();
            $users=DB::table('users')->select(DB::raw('count(id) as contadorusers'))->get()->first();
            $estado_robot=file("../robot_status")[0];
            if($estado_robot=="1"){
                $estado_robot=true;
            }
            else {
                $estado_robot=false;
            }
            return view("admin",['datos'=>$datos,'contkeywords'=>$keywords->contadorkeywords,'contdirecciones'=>$direcciones->contadordirecciones,'contsitios'=>$sitios->contadorsitios,'contusers'=>$users->contadorusers,'estadorobot'=>$estado_robot]);
        }
        else{
            return redirect("/login");
        }

    }
}
