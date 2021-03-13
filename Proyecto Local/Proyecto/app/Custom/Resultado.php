<?php
namespace App\Custom;

class Resultado {
  var $titulo;
  var $enlace;
  var $linea;

  function __construct($titulo,$enlace,$linea) {
       $this->titulo=$titulo;
       $this->enlace=$enlace;
       $this->linea=$linea;
   }
   function getTitulo()
   {
       return $this->titulo;
   }
   function getEnlace()
   {
       return $this->enlace;
   }
   function getLinea()
   {
       return $this->linea;
   }
   function setTitulo($titulo)
   {
       $this->titulo=$titulo;
   }
   function setEnlace($enlace)
   {
       $this->enlace=$enlace;
   }
   function setLinea($linea)
   {
       $this->linea=$linea;
   }
   public function __toString()
    {
        return "<div class='resultado'><a class='list-group-item' href='$this->enlace'>$this->titulo</a><span class='list-group-item disabled'>".$this->enlace."</span><span class='list-group-item disabled'>".$this->linea."</span></li></div>";
    }
}
