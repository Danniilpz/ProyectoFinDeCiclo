<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;

class CambiaEmailController extends Controller
{
    function cambiarEmail(Request $request){
        if(Auth::check()){
            if(!$request->has('newemail')||trim($request->input('newemail'))==""||!filter_var($request->input('newemail'), FILTER_VALIDATE_EMAIL)){
                Session::flash('error', 'Introduce un email válido.');
                return redirect()->route('settings');
            }
            else if(DB::table('users')->where('email', $request->input('newemail'))->count()>0){
              Session::flash('error', 'El email que has introducido ya está en uso.');
              return redirect()->route('settings');
            }
            else {
                Log::info('Usuario '.Auth::user()->id.' cambió su email de '.DB::table('users')->where('id', Auth::user()->id)->pluck('email')[0]." a ".$request->input('newemail'));
                DB::table('users')->where('id', Auth::user()->id)->update(['email' => $request->input('newemail'),'email_verified_at'=>NULL]);
                return redirect()->route('settings');
            }
        }
        else{
            return redirect('/login');
        }
    }
}
