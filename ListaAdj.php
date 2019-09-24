<?php
//namespace teste;
require_once("Aresta.php");

//use \ArrayObject;
class ListaAdj{
    public $no;//setado como obj Nodo
    public $vizinhos;
    public $f;//função de avaliação (f = g + h)
    public $pai;//atual pai durante execução do nodo

    public function __construct(){
        $this->vizinhos = new ArrayObject();
    }
    public function setNo($no){
        $this->no = $no;
    }
    public function getNo(){
        return $this->no;
    }
    public function addAresta(Aresta $aresta){
        //$this->vizinhos->offsetSet($aresta->getPontoB(),$aresta);//offsetSet: (chave,valor)
        $this->vizinhos->append($aresta);
    }
    public function vizinhosArestas(){
        //print_r($this->vizinhos);
        return $this->vizinhos;
    }
    public function setF($f){
        $this->f = $f;
    }
    public function getF(){
        return $this->f;
    }
    public function setPai($pai){
        $this->pai = $pai;
    }
    public function getPai(){
        return $this->pai;
    }

    //modelo: $this->livros->offsetSet($livro->getTitulo(),$livro);

    /*public function setAresta($aresta){
        $this->aresta = $aresta;
    }
    public function getAresta(){
        return $this->aresta;
    }*/
}

?>