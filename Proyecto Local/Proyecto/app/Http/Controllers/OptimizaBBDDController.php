<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;

class OptimizaBBDDController extends Controller
{
    function optimizar(Request $request){
        if(Auth::check()&&Auth::user()->id==1){
            DB::raw("OPTIMIZE TABLE keywords");
            Session::flash('success', 'Se ha optimizado la base de datos con éxito.');
            Log::info('Se optimizo la base de datos.');
            return redirect()->route('admin');
        } else{
            return redirect('/login');
        }
    }
}
