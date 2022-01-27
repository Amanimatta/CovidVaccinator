<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
<?php 
if(!isset($_POST['mapBtn'])){
  die(print_r('You do not have access to this page!!'));
}
  //get provider_id from patient_dashboard
$postProviderId=intval($_POST['mapBtn']);


//get user_id from dashboard
$patient_id = intval($_GET['user']);

//echo $_POST['mapBtn'];


    ?>
<html>
  <body>
    <div id="map"></div>

    <script>
      var customLabel = {
        Home: {
          label: 'A'
        },
        centre: {
          label: 'B'
        }
      };
      
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(40.741895, -73.989308),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;
          var pts = <?php echo json_encode($patient_id); ?>;
          var prd = <?php echo json_encode($postProviderId); ?>;
          var url = 'https://localhost/covidvaccinator/controller/xmlMaker.php?patient_id='+pts+'&provider_id='+prd
          // Change this depending on the name of your PHP or XML file
          downloadUrl(url, function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
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
    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXCDnfDp8az4JKMG_yxgAk41Wf-o7QoHQ&callback=initMap">
    </script>

  </body>
</html>