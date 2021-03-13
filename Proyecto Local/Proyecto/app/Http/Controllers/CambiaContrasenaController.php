<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Session;

class CambiaContrasenaController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'newpass' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    function cambiarContrasena(Request $request){
        if(Auth::check()){
            if(!$request->has('oldpass')||trim($request->input('oldpass'))==""||!$request->has('newpass')||trim($request->input('newpass'))==""||!$request->has('renewpass')){
                Session::flash('error', 'Introduce tu antigua contraseña y la nueva contraseña.');
                return redirect()->route('settings');
            }
            else if($request->input('newpass')!=$request->input('renewpass')){
                Session::flash('error', 'Las contraseñas no coinciden.');
                return redirect()->route('settings');
            }
            else if(strlen($request->input('newpass'))<8){
                Session::flash('error', 'La nueva contraseña debe tener como mínimo 8 caracteres.');
                return redirect()->route('settings');
            }
            else {
                $oldpass=$request->input('oldpass');
                $newpass=$request->input('newpass');
                if(!Hash::check($oldpass,Auth::user()->password)){
                    Session::flash('error', 'La contraseña es incorrecta.');
                    //Session::flash('error', $oldpass."       ".Auth::user()->clave);
                    return redirect()->route('settings');
                }
                else if(Hash::check($newpass,Auth::user()->password)){
                    Session::flash('error', 'La nueva contraseña debe ser diferente.');
                    return redirect()->route('settings');
                }
                else{
                    DB::table('users')->where('id', Auth::user()->id)->update(['password' => Hash::make($newpass)]);
                    Log::info('Usuario '.Auth::user()->id.' cambió su contraseña.');
                    Session::flash('success', 'Se ha cambiado tu contraseña.');
                    return redirect()->route('settings');
                }

            }
        }
        else{
            return redirect('/login');
        }
    }
}
