<?php

class Noticia {

    private $id, $titulo, $noticia, $autor, $pais;

    public function __construct($id, $titulo, $noticia, $autor, $pais) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->noticia = $noticia;
        $this->autor = $autor;
        $this->pais = $pais;
    }

    public function getTitulo(){ return $this->titulo; }
    public function getNoticia(){ return $this->noticia; }
    public function getAutor(){ return $this->autor; }
    public function getPais(){ return $this->pais; }
}