<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ListadoUsersController extends Controller
{
    public function obtenerlistado(Request $request){
        if(Auth::check()&&Auth::user()->id==1){
            $registros=DB::table('users')
                ->orderBy('created_at')
                ->get()
                ->toArray();

            $numreg=count($registros); //numero total de registros

            $numpaginas=$numreg/20;
            $regs=explode(".",$numpaginas);
            if(count($regs)>1){
                $numpaginas=$regs[0]+1;
            }
            if(!$request->has('page')||$request->input('page')==1){
                $skip=0;
                $pagina=1;
            }
            else if($request->has('page')&&$request->input('page')<=$numpaginas){
                $pagina=$request->input('page');
                $skip=($pagina-1)*20;
            }
            else{
                return redirect('/');
            }

            $registros=array_slice($registros,$skip,20); //obtengo solo 20 registros

            return view("usuarios",[
                'registros'=>$registros,
                'paginas'=>$numpaginas,
                'actual'=>$pagina
            ]);

        }
        else{
          return redirect('/login');
        }
    }
}
