<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class RobotController extends Controller
{
    function start(Request $request){
        if(Auth::check()&&Auth::id()==1){
            if(file_exists(base_path()."/robot_status")){

            }
            $f=fopen(base_path()."/robot_status","w");
            flock($f,2);
            fwrite($f,"1");
            flock($f,3);
            fclose($f);
            Session::flash('success', 'Se ha reanudado la ejecución del robot.');
            return redirect()->route('admin');
        }
        else{
            return redirect('/login');
        }
    }
    function stop(Request $request){
        if(Auth::check()&&Auth::id()==1){
            if(file_exists(base_path()."/robot_status")){

            }
            $f=fopen(base_path()."/robot_status","w");
            flock($f,2);
            fwrite($f,"0");
            flock($f,3);
            fclose($f);
            Session::flash('warning', 'Se ha detenido la ejecución del robot.');
            return redirect()->route('admin');
        }
        else{
            return redirect('/login');
        }
    }
}
