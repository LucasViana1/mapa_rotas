<?php
include_once("conexao.php");
include("Aresta.php");
include("Nodo.php");
include("ListaAdj.php");

//TESTE A* DE ARAD A BUCHAREST: banco, entrada e tratamento de dados iniciais para o algoritmo podem ser provisórios
$nodos = mysqli_query($conn, "SELECT * FROM nodo");
//os proximos dois whiles preenche o retorno da cunsulta no banco em um array
while($dadosNodo = mysqli_fetch_array($nodos)){
    /*echo $dadosNodo['id']."<br>";
    echo $dadosNodo['nome']."<br>";
    echo $dadosNodo['tipo']."<br>";
    echo $dadosNodo['h']."<br>";*/

    $nodo = new Nodo();
    $nodo->setIdNodo($dadosNodo['id']);
    $nodo->setNome($dadosNodo['nome']);
    $nodo->setTipo($dadosNodo['tipo']);
    $nodo->setH($dadosNodo['h']);
    $arrayNodo[] = $nodo;
}

$arestas = mysqli_query($conn, "SELECT * FROM aresta");
while($dadosAresta = mysqli_fetch_array($arestas)){
    /*echo $dadosAresta['ponto_a']."<br>";
    echo $dadosAresta['ponto_b']."<br>";
    echo $dadosAresta['peso']."<br>";*/

    $aresta = new Aresta();
    //Percorre array de nodos para inserir os corretos em pontoA e pontoB da aresta
    foreach($arrayNodo as $valorNodo){
        if($valorNodo->getIdNodo() == $dadosAresta['ponto_a']){
            $aresta->setPontoA($valorNodo->getIdNodo());
        }
        if($valorNodo->getIdNodo() == $dadosAresta['ponto_b']){
            $aresta->setPontoB($valorNodo->getIdNodo());
        }
    }
    //add peso aresta
    $aresta->setDistancia($dadosAresta['peso']);
    $arrayAresta[] = $aresta;
}

/*echo "INICIO";
print_r($arrayAresta);
echo "FIM";*/

//o tamanho de listadj é igual ao numero de nodos: count($arrayNodo), loop abaixo percorre cada nodo
foreach($arrayNodo as $valorNodo){
    $listadj = new ListaAdj();
    $listadj->setNo($valorNodo);
    //procura o nodo verificado atualmente no array de arestas
    foreach($arrayAresta as $valorAresta){
        if($valorAresta->getPontoA() == $valorNodo->getIdNodo()){
            //se encontrado a combinação do pontoA da aresta e o atual nodo que está sendo analisado, o pontoB equivaleria ao vizinho do atual nodo que está sendo analisado
            $listadj->addAresta($valorAresta);
        }
    }
    $arrayListaAdj[] = $listadj;//copula array de lista de adjacencia
}

//Visualizando array da lista de adjacencia já preenchida
//print_r($arrayListaAdj);
echo "<br>";


foreach($arrayListaAdj as $ListaAdj){
     //print_r($ListaAdj->getNo()->getTipo());

     //inicia a lista de "abertos" com nodo inicial
     if($ListaAdj->getNo()->getTipo() == 'origem'){
         $abertos[] = $ListaAdj;
        //$fechados[] = $ListaAdj;//TALVEZ REMOVER
         break;
     }

    //print_r($ListaAdj);
   // print_r($ListaAdj->getNo());
    //var_dump($ListaAdj);
    echo "<br>";
}

//print_r($abertos[0]->getNo());
$atual = $abertos[0]->getNo();//recebe o atual candidato a ser o nodo objetivo, ou seja, nó que esta sendo verificado atualmente no fluxo da busca
//print_r($atual->getTipo());
//$fechados[] = new ListaAdj();
$fechados = array();
while($atual->getTipo() != 'destino'){//enquanto não for encontrado o nodo objetivo
    foreach($arrayListaAdj as $ListaAdj){//procura os filhos do nó atual
        if($ListaAdj->getNo()->getIdNodo() == $atual->getIdNodo()){//localiza o nodo na listaAdj, classe que armazena os vizinhos do nodo analisado(vizinhos = filhos)
            //define nodo 'atual' como pai dos dos filhos gerados (VER SE DA ERRO NA PRIMEIRA EXECUÇÃO, POIS O PONTO DE ORIGEM NÃO TEM VALOR DA FUNÇÃO DE AVALIAÇÃO)
            
            // print_r($ListaAdj->vizinhosArestas()[0]->getPontoA());
            foreach($ListaAdj->vizinhosArestas() as $filhos){//calcula função de avaliação em cada filho do nodo atual e o insere em abertos, filhos são do tipo Aresta
                //print_r($filhos);//exibe filhos
                //função onde dado o id de um nodo, é retornado seu valor heuristico
                //$heuristica =  valorHeuristico($arrayNodo, $filhos->getPontoB());
                $heuristica =  valorHeuristico($arrayNodo, $filhos->getPontoB());

                $x = valorHeuristico($arrayNodo, $ListaAdj->getNo()->getIdNodo());
                if($ListaAdj->getF() == null) {//define valor 0 para f do nodo de origem, não é o certo mas permite que não haja erro durante a execução do fluxo
                    //zera valores se for nodo de origem
                    $ListaAdj->setF(0);
                    $x = 0;
                    echo "KKKKKK";
                }
                //$ListaAdj->setPai();
                echo "^^";
                print_r($ListaAdj->getNo()->getIdNodo());
                echo "^^";
                echo '<br>';
                echo "h: ".$x."<br>";
                echo "f: ".$ListaAdj->getF()."<br>";
                //calculo função de avaliação (INICIALMENTE SERÁ USADO APENAS A DISTÂNCIA, POSTERIORMENTE SERÁ USADO EM PARALELO O TEMPO)
                $f = $filhos->getDistancia() + $heuristica + ($ListaAdj->getF() - $x);// f = g + h, g é a distancia acumulada
                echo 'Função de avaliação: '.$f;
                echo "<br>";
                //localiza na listadj o filho e adiciona esse objeto em 'abertos', insere valor de f ao objeto do filho ja em 'abertos'
                foreach($arrayListaAdj as $ADJ){
                    
                    if($ADJ->getNo()->getIdNodo() == $filhos->getPontoB()){
                        array_push($abertos, $ADJ);//insere no final do array de 'abertos'
                        //insere valor da função de avaliação ao filho ja adicionado em 'abertos', dessa forma garante integridade do array principal de listaAdj (sem auterar seus valores para calculos futuros)
                        $abertos[count($abertos) - 1]->setF($f);//basicamente insere no ultimo elemento adicionado ao array
                    }
                }                
            }
        }
    }

    //verifica em 'abertos' qual tem o menor valor de f, e faz com que ele se torne o novo nodo atual a ser verificado no teste de objetivo
    $menor = $abertos[1]->getF();//insere o valor de um nodo do tipo intermediario para inicializar a comparação de menor f, testar se essa é a melhor solução
    foreach($abertos as $item){

        $noInfinityLoop = 0;
        //verifica se nodo se encontra em 'fechados', nesse caso ele não pode ser utilizado pois entrará em loop infinito.
        foreach($fechados as $fec){
            //if($fecha->)
            //echo "<br>GAR: ";
            //print_r($fec);
            //print_r($item->getNo()->getIdNodo());
            if($fec == $item->getNo()->getIdNodo()){//caso id do nodo se encontra em 'fechados'
                $noInfinityLoop = 1;
            }
        }

        if($item->getNo()->getTipo() != 'origem' && $noInfinityLoop == 0){//sempre ignora o nodo de origem, talvez melhorar ess trecho
            //mantem atualizado o menor f encontrado
            if($item->getF() < $menor){
                $menor = $item->getF();
                $candidato = $item->getNo();//armazena o objeto do nodo identificado como melhor candidato a proxima etapa de teste de objetivo
            }
        }
    }

    //insere o antigo 'atual' em 'fechados'
    //CODIGO AQUI

    array_push($fechados,$atual->getIdNodo());//MELHORAR OU REMOVER
    echo "fechados: ";
    print_r($fechados);
    
    //o candidato encontrado se torno o 'atual'
    $atual = $candidato;

    //print_r($atual);
    echo '<br>';
    //print_r($abertos);

    //break;//PARA TESTES, REMOVER DEPOIS
}
//print_r($fechados);

//função retorna o valor heuristico dado o id de um nodo, recebe como parametro o arrayNodo (contem listagem de todos os nodos) e o id do nodo a ser localizado
function valorHeuristico($arrayNodo, $idNo){

    foreach($arrayNodo as $listaNodos){
        if($listaNodos->getIdNodo() == $idNo){
            return $listaNodos->getH();
        }

       
    }
}

/**Algoritmo A estrela
 * Criar estruturas temporárias para organização da selação
 * função f = g + h
 * h: getH()
 * g: peso acumulado, no fluxo de busca atual
 * f: soma peso e heuristica, usado para avaliar demais caminhos
 * acumulador: variavel contera a distancia de todo o trajeto real (sem heuristica) ate o momento (equivale ao g anterior)
 * criar função que dado o id de um nodo, procurar no array de nodo o objeto correspondente e retorna-lo (ou atributo desejado do obj)
 * lista de abertos: nós gerados e com função f calculado, mas seus sucessores não foram gerados
 * lista de fechados: nós que ja foram de abertos, e agr tiveram seus filhos gerados (usados para saber se o nó ja foi gerado antes)
 */



//TESTES DE COPULAÇÃO DE OBJETOS (SEM LOOP)
/*//Setando valor nodo
$nodo->setH(1);
$nodo->setTipo(2);
$nodo->setIdNodo(3);
//Setando valor aresta
$aresta->setPontoA($nodo);
$aresta->setPontoB($nodo);
$aresta->setDistancia(30);
$aresta->setTempo(10);
//exibindo valores
echo $aresta->getPontoA()->getIdNodo()."<br>";
print_r($aresta);
$listadj->addAresta($aresta);
echo "<br>";
print_r($listadj);
//$listadj->listaArestas();*/

//testando array


//$listadj->no = 5;
//echo $listadj->no;
//$listadj->setNo(5);
//echo $listadj->getNo();

//criar classe Nodo e Aresta
//Nodo: terá variavel lat e lng, variavel heuristica, tipo (origem,destino,intermediario), rotulo (id), talvez nome do ponto (string)
//Aresta: dois objetos de Nodos (ponto a e b), dois tipos peso aresta (km e tempo)


?>