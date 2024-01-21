<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARLA</title>
    <style>
        #map {
            height: 400px;
            width: 600px;
            
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        function initMap() {
            // Google Maps API anahtarınızı buraya ekleyin
            var apiKey = 'AIzaSyAs8PxoTvmpRquHtBdLFUTenMFLQbEuJBI';

            // Harita nesnesini oluşturun
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 39.5672867, lng: 26.7424842}, // İlk merkez konumu
                zoom: 8 // Yakınlaştırma seviyesi
            });

            // Marker'ları çekmek için AJAX kullan
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var markersData = JSON.parse(this.responseText);

                    // Her bir marker için döngü
                    markersData.forEach(function(markerData) {
                        var marker = new google.maps.Marker({
                            position: {lat: markerData.lat, lng: markerData.lng},
                            map: map,
                            title: markerData.title
                        });
                    });
                }
            };

            xhr.open("GET", "get_markers.php", true);
            xhr.send();
        }
    </script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAs8PxoTvmpRquHtBdLFUTenMFLQbEuJBI&callback=initMap">
    </script>
<a href="add_markers.php">
    <button>Harita Ekle</button>
  </a> </body>
</html>
