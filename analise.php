<!DOCTYPE html>
<html>
  <head>
    <!--<meta name="viewport" content="initial-scale=1.0">-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <link rel="stylesheet" href="analise.css">
    <link href="bootstrap4/css/bootstrap.min.css" rel="stylesheet">
    <title>Analise</title>
  </head>
  <body>
  <!--grafo resultante-->
  <div id="grafo">
    <canvas id="viewport" width="800" height="600"></canvas>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
  </div>

  <!--relatorio de resultados-->
  <div id="relatorio_resultados">
    <h6>Legenda: </h6><br>
    <label>A-> Posto 67; C-> Posto Shell; B-> Unip; D-> Posto BR</label>
    <br><hr>
    <h6>Ordem do percurso: </h6><br>
    <label>A,C,B,...</label>
    <hr>

    <label>Tempo de execução do algoritmo A*: </label><br>
    <label>...</label>
  </div>




  <script src="arbor/lib/arbor.js"></script>
  <script src="analise.js"></script>

  </body>
</html>
