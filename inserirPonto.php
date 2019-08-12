<?php
//arquivo responsavel por cadastrar um registro de ponto no banco
session_start();
//ob_start();
include_once("conexao.php");

//Receber os dados do formulário
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//Salvar os dados no bd
/*$result = "INSERT INTO markers(name, address, lat, lng, type)
				VALUES
				('".$dados['nome_ponto']."', '".$dados['endereco_ponto']."', '".$dados['coordx_ponto']."',
		  '".$dados['coordy_ponto']."', '".$dados['tipo_ponto']."')";*/
$result = "INSERT INTO tblnodo(nome_ponto, endereco, lat, lng, tipo)
			VALUES
			('".$dados['nome_ponto']."', '".$dados['endereco_ponto']."', '".$dados['coordx_ponto']."',
		'".$dados['coordy_ponto']."', '".$dados['tipo_ponto']."')";

$resultado_markers = mysqli_query($conn, $result);



if(mysqli_insert_id($conn)){
	$_SESSION['msg'] = "<span style='color: green';>Endereço cadastrado com sucesso!</span>";
	header("Location: mapa.php");
}else{
	$_SESSION['msg'] = "<span style='color: red';>Erro: Endereço não foi cadastrado com sucesso!</span>";
	header("Location: mapa.php");
}
