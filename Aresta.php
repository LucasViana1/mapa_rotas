<?php

class Aresta{
    public $pontoA;//objeto Nodo
    public $pontoB;//objeto Nodo
    public $tempo;
    public $distancia;

    public function setPontoA($pontoA){
        $this->pontoA = $pontoA;
    }
    public function getPontoA(){
        return $this->pontoA;
    }
    public function setPontoB($pontoB){
        $this->pontoB = $pontoB;
    }
    public function getPontoB(){
        return $this->pontoB;
    }
    public function setTempo($tempo){
        $this->tempo = $tempo;
    }
    public function getTempo(){
        return $this->tempo;
    }
    public function setDistancia($distancia){
        $this->distancia = $distancia;
    }
    public function getDistancia(){
        return $this->distancia;
    }
}

?>