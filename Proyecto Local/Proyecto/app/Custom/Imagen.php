<?php
namespace App\Custom;

class Imagen {
    var $imagen;
    var $enlace;

    function __construct($imagen,$enlace) {
        $this->imagen=$imagen;
        $this->enlace=$enlace;
    }
    function setImagen($imagen)
    {
        $this->imagen=$imagen;
    }
    function setEnlace($enlace)
    {
        $this->enlace=$enlace;
    }
    function getImagen()
    {
        return $this->imagen;
    }
    function getEnlace()
    {
        return $this->enlace;
    }
    public function __toString()
    {
        return "<div class='registroimagen col-md-3 col-sm-4 col-6'><a class='titulo' href='$this->enlace'><img src='$this->imagen'/></a></div>";
    }
}
