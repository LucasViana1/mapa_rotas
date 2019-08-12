<?php
//arquivo responsavel por armazenar os dados da aresta (pontos que se conectam, distancia em km, tempo em horas/minutos)
session_start();
ob_start();
include_once("conexao.php");

//Receber os dados do formulário
$dados_dist = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$km = '/km/'; $horas = '/horas/';
$ori = ''; $dest = '';
$insert = "INSERT INTO tblaresta(ponto_a, ponto_b, km, tempo) VALUES";//constante do INSERT
$ponto_dist; $res_aresta; 

foreach($dados_dist as $valor){
    if(preg_match($km, $valor)){
        echo $valor;
        echo "<br>id origem: ".$ori." - id destino: ".$dest; 
        $ponto_dist = "('".$ori."', '".$dest."', '".$valor."',"; //inserindo os VALUES de ponto_a,ponto_b e distancia
        $res_aresta = $insert.$ponto_dist;//preparação inicial da query 
        $ori = '';//variaveis auxiliares sendo 'limpas'
        $dest = '';  
    }
    elseif(preg_match($horas, $valor)){
        echo $valor;    
        $res_aresta = $res_aresta." '".$valor."')";//inserindo ultimo dado de VALUES: tempo
        $res = mysqli_query($conn, $res_aresta);//realizando a query no banco
    }
    else{
        if($ori == ''){
            $ori = $valor;
        }
        else{
            $dest = $valor;
        }
    }
    //echo $valor;
    echo "<br>";
}

//$res = mysqli_query($conn, "");
//talvez usar a query de DELETE a seguir para excluir registros repetidos:
//DELETE FROM tblaresta WHERE ponto_a > ponto_b

//header("Location: mapa.php");