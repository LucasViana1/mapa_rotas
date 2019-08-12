<?php
//arquivo responsavel por excluir um registro de ponto no banco
require_once 'conexao.php';
$id_ponto = $_POST['id_ponto'];//recebe o id do banco

//verifica se existe conteudo no "id"
if (isset($_POST['id_ponto'])){
  //exclusao do registro no id especificado
  //echo "oi";
    //$sql = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());
  //$sql = mysqli_query($conn, "DELETE FROM markers WHERE id=".$id_ponto)  or die(mysqli_error());
  $sql = mysqli_query($conn, "DELETE FROM tblnodo WHERE id=".$id_ponto)  or die(mysqli_error());
}
//destroi a variavel que recebeu o valor de id
unset($_POST['id']);
header('location:mapa.php');

?>
