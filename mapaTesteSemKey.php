<!DOCTYPE html>
<html>
  <head>
    <title>Mapa</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link href="bootstrap4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="mapa.css">
    <?php require_once 'conexao.php';
    session_start();
    ?>
  </head>
  <body>

    <!--menu-->
    <div class="row">
      <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <a class="navbar-brand" href="#">Menu</a>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Voltar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calculaAresta.php">Calcula Aresta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="heuristica.php">Calcula Heuristica</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="analise.php">Representação em Grafo</a><!--Analise-->
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Sair</a>
          </li>
        </ul>
      </nav>
    </div>

    <!--mapa-->
    <div id="map"></div>

    <!--relatorio-->
    <div id="relatorio" class="table-overflow">
       <h5>Plano de viagem, pontos cadastrados:</h5><br>

       <?php
          include 'conexao.php';

          if(isset($_POST['nome_ponto'])){
            $nome_ponto = $_POST['nome_ponto'];
            if ($nome_ponto != "") {

              //$sql = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());
              $sql = mysqli_query($conn, "SELECT * FROM tblnodo")  or die(mysqli_error());

              if (mysqli_num_rows($sql) <= 0){
                echo "Nenhum registro encontrado";
              }
              else{
                listagem($sql);
              }
            }
          }
          else{
              //$sql = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());
              $sql = mysqli_query($conn, "SELECT * FROM tblnodo")  or die(mysqli_error());
            listagem($sql);
          }

          function listagem($sql){
            if (mysqli_num_rows($sql) > 0) {
              //echo "<div class=\"row\">";
             while ($dados = mysqli_fetch_array($sql)) {
                echo "<ul class='container-fluid row border border-primary cadastrados'>";
                echo "<li class=\"id_p col-12\"><b>ID:</b> ".$dados['id']."</li><br>";
                echo "<li class=\"name_p col-12\"><b>Nome:</b> ".$dados['nome_ponto']."</li><br>";
                echo "<li class=\"addr_p col-12\"><b>Endereço:</b> ".$dados['endereco']."</li><br>";
                echo "<li class=\"lat_p col-12\"><b>Latitude:</b> ".$dados['lat']."</li><br>";
                echo "<li class=\"lng_p col-12\"><b>Longitude:</b> ".$dados['lng']."</li><br>";
                echo "<li class=\"type_p col-12\"><b>Tipo:</b> ".$dados['tipo']."</li><br>";
                echo "<form class=\"col-6 mt-1\" action=\"excluirPonto.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"id_ponto\" value=\"" . $dados['id'] . "\">";
                echo "<button class='btn btn-danger' type=\"submit\" name=\"botao_excluir\">Excluir</button>";
                echo "</form>";
                echo "<p class='btn btn-success mt-1 col-4' onclick=\"calcularDist(".$dados['lat'].",".$dados['lng'].",".$dados['id'].")\">Distâncias</p>";
                echo "</ul>";
                echo "<hr>";  
              }
            }
          }
        ?>
    </div>

    <!--cadastro de ponto-->
    <div id="cadastro_ponto" class="">
      <h5>Formulário de cadastro de pontos</h5>
      <form class="form_cadastro_ponto" action="inserirPonto.php" method="post">
        <label for="">Nome</label>
        <input type="text" name="nome_ponto">
        <label for="">Coordenada x</label>
        <input type="text" name="coordx_ponto">
        <label for="">Coordenada y</label>
        <input type="text" name="coordy_ponto">
        <label for="">Endereço</label>
        <input type="text" name="endereco_ponto" size="70">
        <br>
        <label>Tipo</label>
        <select class="" name="tipo_ponto">
          <option value="origem">Origem</option>
          <option value="destino">Destino</option>
          <option value="intermediario">Intermediario</option>
        </select>
        <br>
        <input type="submit" class="btn btn-info mb-2 mt-2 ml-1" name="botao_cadastra_ponto" value="Cadastrar Ponto">
        <?php
          if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
          }
        ?>
      </form>
    </div>
    <div id="calculo_dist">
      <h1>Cálculo das distâncias</h1>
      <!--<div>
        <strong>Results</strong>
      </div>-->
      <div id="output"></div>
      <div>
        <strong id="test"><h5>Resultados adaptados</h5></strong>
      </div>

      <!--form cadastra distancia calculada no banco-->
      <form action="salvarDistancia.php" class="mr-3" method="post">
      
        <table class='table table-bordered table-striped'>
          <thead>
            <tr>
              <th scope='col'>ID ORIGEM</th>
              <th scope='col'>ENDEREÇO DESTINO</th>
              <th scope='col'>ID DESTINO</th>
              <th scope='col'>ENDEREÇO DESTINO</th>
              <th scope='col'>DISTÂNCIA TRAJETO</th>
              <th scope='col'>TEMPO TRAJETO</th>
            </tr>
          </thead>
          
      
          <!--conteudo formulario, valores de resposta do calculo da distancia de pontos-->
          <tbody id="dist"></tbody>
        
          
        </table>

        <button class="btn btn-primary mb-3 mt-3 ml-1" type="submit">Salvar distâncias geradas</button>
      </form>

    </div>
    </div>

    <script>
      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-25.494938, -49.294372),
          zoom: 5
        });
        var infoWindow = new google.maps.InfoWindow;

        // Change this depending on the name of your PHP or XML file
        downloadUrl('resultado.php', function(data) {
          var xml = data.responseXML;
          //var markers = xml.documentElement.getElementsByTagName('marker');
          var markers = xml.documentElement.getElementsByTagName('nodos');
          Array.prototype.forEach.call(markers, function(markerElem) {
            /*var name = markerElem.getAttribute('name');
            var address = markerElem.getAttribute('address');
            var type = markerElem.getAttribute('type');
            var point = new google.maps.LatLng(
              parseFloat(markerElem.getAttribute('lat')),
              parseFloat(markerElem.getAttribute('lng')));*/
            var name = markerElem.getAttribute('nome_ponto');
            var address = markerElem.getAttribute('endereco');
            var type = markerElem.getAttribute('tipo');
            var point = new google.maps.LatLng(
              parseFloat(markerElem.getAttribute('lat')),
              parseFloat(markerElem.getAttribute('lng')));

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = address
            infowincontent.appendChild(text);
            var icon = customLabel[type] || {};
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              label: icon.label
            });
            marker.addListener('click', function() {
              infoWindow.setContent(infowincontent);
              infoWindow.open(map, marker);
            });
          });
        });
    }//fim initMap()

    function deleteMarkers(markersArray) {
      for (var i = 0; i < markersArray.length; i++) {
        markersArray[i].setMap(null);
      }
      markersArray = [];
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;
          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request, request.status);
            }
          };
          request.open('GET', url, true);
          request.send(null);
    }
    function doNothing() {}
    </script>

    <!--calcula distancia-->
    <script>
    function calcularDist(lat,lng,idOri){
      var bounds = new google.maps.LatLngBounds;
      var markersArray = [];
      var origem = new google.maps.LatLng(lat,lng);
      var idOrigem = idOri;

      <?php
      echo "var destination = [];";//vetor c/ par de coordenadas dos pontos
      echo "var idDestination = [];";//vetor c/ id dos pontos 
      //$pontos = mysqli_query($conn, "SELECT * FROM markers ORDER BY id")  or die(mysqli_error());
      $pontos = mysqli_query($conn, "SELECT * FROM tblnodo ORDER BY id")  or die(mysqli_error());
      $x = 0;
      while ($dados_p = mysqli_fetch_array($pontos)) {
        echo "destination[".$x."] = new google.maps.LatLng(".$dados_p['lat'].", ".$dados_p['lng'].");";
        echo "idDestination[".$x."] = new Number('".$dados_p['id']."');"; $x++;
      }
      ?>

      var destinationIcon = 'https://chart.googleapis.com/chart?' +
        'chst=d_map_pin_letter&chld=D|FF0000|000000';
      var originIcon = 'https://chart.googleapis.com/chart?' +
        'chst=d_map_pin_letter&chld=O|FFFF00|000000';

      var geocoder = new google.maps.Geocoder;

      <?php
      /*$qtd_query = mysqli_query($conn, "SELECT * FROM markers")  or die(mysqli_error());
      $qtd = mysqli_num_rows($qtd_query);
      echo "var cont = 0;";
      echo "for(cont = 0; cont < ".$qtd."; cont++){";*/
      //echo "for(var j = 0; j < i; j++){";
      ?>
      var cont = -1;
      idDestination.forEach(function(id_val){
        cont = cont + 1;
      
      var service = new google.maps.DistanceMatrixService;
      service.getDistanceMatrix({
        origins: [origem],
        destinations: [destination[cont]],
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
      },
      function(response, status) {
        if (status !== 'OK') {
          alert('Erro: ' + status);
          console.log('Erro: ' + status)
      } else {
          var originList = response.originAddresses;
          var destinationList = response.destinationAddresses;
          var outputDiv = document.getElementById('output');
          var distDiv = document.getElementById('dist');
          outputDiv.innerHTML = '';//TALVEZ REMOVER ESSA LINHA
          //distDiv.innerHTML = '';
          deleteMarkers(markersArray);

          var showGeocodedAddressOnMap = function(asDestination) {
            var icon = asDestination ? destinationIcon : originIcon;
            return function(results, status) {
              if (status === 'OK') {
                map.fitBounds(bounds.extend(results[0].geometry.location));
                markersArray.push(new google.maps.Marker({
                  map: map,
                  position: results[0].geometry.location,
                  icon: icon
                }));
              } else {
                  //alert('Geocode was not successful due to: ' + status);
                  console.log('Geocode was not successful due to: ' + status)
                }
            };
          };

          //TALVEZ REMOVER OS PROXIMOS DOIS FOR, POIS OCORRE APENAS UMA EXECUÇÃO
          for (var i = 0; i < originList.length; i++) {
            var results = response.rows[i].elements;
            geocoder.geocode({'address': originList[i]},
            showGeocodedAddressOnMap(false));
            
            for (var j = 0; j < results.length; j++) {
              geocoder.geocode({'address': destinationList[j]},
              showGeocodedAddressOnMap(true));
              //CONTINUAR DAQ
              /*outputDiv.innerHTML += originList[i] + ' PARA ' + destinationList[j] +
              ': ' + results[j].distance.text + ' EM ' +
              results[j].duration.text + '<br>';*/

              distDiv.innerHTML += 
              /*'<br>' + 'id: '+ idOrigem + ' -> ' + originList[i] + ' PARA ' + 'id: '+ id_val +' -> '+ destinationList[j] +
              ': ' + results[j].distance.text + ' EM ' + results[j].duration.text + '<br>' +*/
              '<input type="hidden" name="id_origem_'+ idOrigem +'_'+ id_val + '" value="'+ idOrigem +'">' +
              '<input type="hidden" name="id_destino_'+ idOrigem +'_'+ id_val + '" value="'+ id_val +'">' +
              '<input type="hidden" name="distancia_'+ idOrigem +'_'+ id_val + '" value="'+ results[j].distance.text +'">' +
              '<input type="hidden" name="duracao_'+ idOrigem +'_'+ id_val + '" value="'+ results[j].duration.text +'">'  +           
              
              '<tr>'+
                '<td>'+idOrigem+'</td>'+
                '<td>'+originList[i]+'</td>'+
                '<td>'+id_val+'</td>'+
                '<td>'+destinationList[j]+'</td>'+
                '<td>'+results[j].distance.text +'</td>'+
                '<td>'+results[j].duration.text+'</td>'+
              '</tr>'   
              
              ;
              //DEFINIR LAYOUT DE FORMULARIO, QUE AO DAR SUBMIT, SALVE OS DADOS NO BANCO
              
              
            }
          }
        }
      });

    });//loop teste com foreach
  }//funcao calcDist
      
    </script>

    <script src="LINK+CHAVE API"
    async defer></script>
  </body>
</html>
