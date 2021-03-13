<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "SettingsController@cargaConfiguracion")->name('home');
Route::get('/imagenes', "SettingsController@cargaConfiguracion")->name('images');

Route::get('/busqueda', "BusquedaController@busca")->name('busqueda');
Route::get('/imagenes/busqueda', "BusquedaImagenesController@busca")->name('busquedaimages');

Route::match(['get', 'post'],'/alta', "AltaController@alta")->name('alta')->middleware('verified');

Route::get('/historial', "HistoryController@leerHistorial")->name('historial')->middleware('verified');
Route::get('/historialcompleto', "HistorialCompletoController@leerHistorial")->name('historialcompleto');;
Route::get('/limpiarhistorial', "BorraHistorialController@borrar")->middleware('verified');
Route::get('/listadousuarios', "ListadoUsersController@obtenerlistado");
Auth::routes(['verify' => true]);

Route::get('/home', function () {
    return redirect("/");
});

Route::get('/admin', "PanelAdminController@cargaPanel")->name('admin');
Route::get('/settings', "SettingsController@cargaConfiguracion")->name('settings')->middleware('verified');
Route::view('/terminos_y_condiciones', "terminos")->name('terminos');
Route::view('/acercade', "acercade")->name('acercade');
Route::view('/cookies', "cookies")->name('cookies');

Route::post('/cambiaemail', "CambiaEmailController@cambiarEmail")->middleware('verified');
Route::post('/cambiacontrasena', "CambiaContrasenaController@cambiarContrasena")->middleware('verified');
Route::post('/cambianombre', "CambiaNombreController@cambiarNombre")->middleware('verified');
Route::post('/preferencias', "CambiaPreferenciasController@cambiarPreferencias")->middleware('verified');
Route::post('/cambiartema', "CambiaTemaController@cambiarTema")->middleware('verified');
Route::get('/borrarimagen', "CambiaTemaController@borrarImagen")->middleware('verified');
Route::match(['get', 'post'],'/eliminarcuenta', "EliminarCuentaController@eliminar")->middleware('verified');
Route::get('/optimizar', "OptimizaBBDDController@optimizar");
Route::get('/eliminarusuario', "EliminarUsuarioController@eliminar");
Route::get('/verificarusuario', "VerificarUsuarioController@verificar");
Route::get('/robot_status', function(){
    echo "<div id='estado'>".file(base_path()."/robot_status")[0]."</div>";
});
Route::get('/robotstart', "RobotController@start");
Route::get('/robotstop', "RobotController@stop");

Route::get("/imagen/{num}",function($num){
  $id=Auth::id();
  $rutaImagen=base_path()."/users/images/$id/$num.jpg";
  if(file_exists($rutaImagen)){
      $informacionImagen = getimagesize($rutaImagen);
			header("Content-type: {$informacionImagen['mime']}");
      readfile($rutaImagen);
  }
});
