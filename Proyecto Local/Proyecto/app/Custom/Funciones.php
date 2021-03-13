<?php
namespace App\Custom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Funciones{
    function countImages($path){
        $dir = opendir($path);
        $files = array();
        while ($current = readdir($dir)){
            if( $current != "." && $current != "..") {
                if(is_dir($path.$current)) {
                    showFiles($path.$current.'/');
                }
                else {
                    $files[] = $current;
                }
            }
        }
        return count( $files );
    }

  public function url_ya_existente($url){
    $encontrado=false;
    $sitios=DB::table('sitios')->pluck('direccion');
    foreach ($sitios as $sitio) {
      if($sitio==$url){
        $encontrado=true;
        break;
      }
    }
    return $encontrado;
  }

  public function file_get_contents_curl($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      return $output;
  }
  public function DOMinnerHTML($element){
    $innerHTML = "";
    $children = $element->childNodes;
    foreach ($children as $child)
    {
        $tmp_dom = new \DOMDocument();
        $tmp_dom->appendChild($tmp_dom->importNode($child, true));
        $innerHTML.=trim($tmp_dom->saveHTML());
    }
    return $innerHTML;
  }

    function eliminar_tildes($cadena){

      //Reemplazamos las letras
      $cadena = str_replace(
          array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
          array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
          $cadena
      );

      $cadena = str_replace(
          array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
          array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
          $cadena );

      $cadena = str_replace(
          array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
          array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
          $cadena );

      $cadena = str_replace(
          array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
          array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
          $cadena );

      $cadena = str_replace(
          array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
          array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
          $cadena );

      $cadena = str_replace(
          array('ñ', 'Ñ', 'ç', 'Ç'),
          array('n', 'N', 'c', 'C'),
          $cadena
      );

      return $cadena;
  }
  function strip_tags_content($text, $tags = '', $invert = FALSE) {

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if(is_array($tags) AND count($tags) > 0) {
      if($invert == FALSE) {
        return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
      }
      else {
        return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
      }
    }
    elseif($invert == FALSE) {
      return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
  }
  function guardaHistorial($search,$tipo){
    $id="0";
    if(Auth::check()){
        $id=Auth::user()->id;
    }
    DB::table('historial')->insert(
      ['busqueda' => $search, 'usuario' => $id, 'tipo' => $tipo, 'fecha' => DB::raw('now()')]
    );
  }
}
