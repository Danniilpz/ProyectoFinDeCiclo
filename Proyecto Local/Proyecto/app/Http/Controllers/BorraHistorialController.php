<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class BorraHistorialController extends Controller
{
    function borrar(){
      if(Auth::check()){
        DB::table('historial')->where('usuario', Auth::user()->id)->update(['usuario' => '0']);
        Session::flash('success', 'Se ha borrado tu historial.');
        return redirect('/historial');
      }
      else{
        return redirect('/login');
      }
    }
}
