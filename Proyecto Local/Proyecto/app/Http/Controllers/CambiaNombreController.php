<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;

class CambiaNombreController extends Controller
{
    function cambiarNombre(Request $request){
        if(Auth::check()){
            if(!$request->has('newname')||trim($request->input('newname'))==""){
                Session::flash('error', 'Introduce un nombre.');
                return redirect()->route('settings');
            } else {
                DB::table('users')->where('id', Auth::user()->id)->update(['name' => $request->input('newname')]);
                Log::info('Usuario '.Auth::user()->id.' cambió su nombre.');
                Session::flash('success', 'Se ha cambiado tu nombre.');
                return redirect()->route('settings');
            }
        } else{
            return redirect('/login');
        }
    }
}
