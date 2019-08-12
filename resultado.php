<?php
//arquivo responsavel por gerar o xml que realiza a integração dos dados do banco com a aplicação
require("conexao.php");

function parseToXML($htmlStr){
	$xmlStr=str_replace('<','&lt;',$htmlStr);
	$xmlStr=str_replace('>','&gt;',$xmlStr);
	$xmlStr=str_replace('"','&quot;',$xmlStr);
	$xmlStr=str_replace("'",'&#39;',$xmlStr);
	$xmlStr=str_replace("&",'&amp;',$xmlStr);
	return $xmlStr;
}

// Select all the rows in the markers table
//$result_markers = "SELECT * FROM markers";
$result_markers = "SELECT * FROM tblnodo";
$resultado_markers = mysqli_query($conn, $result_markers);

header("Content-type: text/xml");

// Start XML file, echo parent node
//echo '<markers>';
echo '<nodos>';

// Iterate through the rows, printing XML nodes for each
while ($row_markers = mysqli_fetch_assoc($resultado_markers)){
  // Add to XML document node
  /*echo '<marker ';
  echo 'name="' . parseToXML($row_markers['name']) . '" ';
  echo 'address="' . parseToXML($row_markers['address']) . '" ';
  echo 'lat="' . $row_markers['lat'] . '" ';
  echo 'lng="' . $row_markers['lng'] . '" ';
  echo 'type="' . $row_markers['type'] . '" ';
  echo '/>';*/
  echo '<nodos ';
  echo 'name="' . parseToXML($row_markers['nome_ponto']) . '" ';
  echo 'address="' . parseToXML($row_markers['endereco']) . '" ';
  echo 'lat="' . $row_markers['lat'] . '" ';
  echo 'lng="' . $row_markers['lng'] . '" ';
  echo 'type="' . $row_markers['tipo'] . '" ';
  echo '/>';
  //TALVEZ ALTERAR O NOME DOS CAMPOS PARA OS MESMOS DA TABELA DE NÓS OU EQUIVALENTE
}

// End XML file
//echo '</markers>';
echo '</nodos>';



