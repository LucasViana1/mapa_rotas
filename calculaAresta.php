<?php
include_once("conexao.php");

/*roteiro:
vetor recebe SELECT da tabela de pontos cadastrados
loop realizara calculo de cada registro na tabela de pontos cadastrados
sera rodado outro SELECT para cada registro, para realizar a inserção dos par na tabela de arestas
*/

$resgistro = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());

while ($dados1 = mysqli_fetch_array($resgistro)) {
    //echo "<div class=\"id_p\">id: ".$dados['id']."</div><br>";
    $par = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());
    while ($dados2 = mysqli_fetch_array($par)){

        if($dados1['id'] != $dados2['id'] && $dados1['id'] < $dados2['id']){
            $result = "INSERT INTO tblaresta(ponto_a, ponto_b)
				VALUES
				('".$dados1['id']."', '".$dados2['id']."')";

            $resultado_aresta = mysqli_query($conn, $result);
        }
        

    }    
}

header("Location: mapa.php");


?>