<?php
$servidor = "127.0.0.1";
$login = "root";
$senha = "";
$banco = "mapa_rotas";

$conexao = mysqli_connect($servidor,$login,$senha,$banco);
$GLOBALS['conexao'] = $conexao;
if (mysqli_connect_errno($conexao))
    echo "Erro ao acessar o banco de dados";
else
    echo "Banco de dados acessado com sucesso";
?>