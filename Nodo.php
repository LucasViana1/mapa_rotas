<?php

class Nodo{
    public $idNodo;
    public $nome;//opcional
    public $h;
    public $tipo;

    public function setIdNodo($idNodo){
        $this->idNodo = $idNodo;
    }
    public function getIdNodo(){
        return $this->idNodo;
    }
    public function setNome($nome){
        $this->nome = $nome;
    }
    public function getNome(){
        return $this->nome;
    }
    public function setH($h){
        $this->h = $h;
    }
    public function getH(){
        return $this->h;
    }
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    public function getTipo(){
        return $this->tipo;
    }
}