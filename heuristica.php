<?php
//arquivo responsavel pelo calculo do valor heuristico
session_start();
include_once("conexao.php");

/* passos:
SELECT para encontrar dados do ponto de destino
SELECT em todos nodos cadastrados
loop acessando cada registro da tabela de nodo
    calcular distancia euclidiana com coordenada de destino e do ponto analisado
    realizar UPDATE no registro analisado, inserindo o valor retornado da função heuristica
*/
?>