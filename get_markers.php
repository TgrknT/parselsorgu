<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proje";

$conn = new mysqli($servername, $username, $password, $dbname);

// Hata kontrolü
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Marker'ları çek
$sql = "SELECT lat, lng, title FROM markers";
$result = $conn->query($sql);

$markers = array();

// Sonuçları döngü ile oku
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // lat ve lng değerlerini sayı türüne dönüştür
        $lat = floatval($row['lat']);
        $lng = floatval($row['lng']);

        $markers[] = array(
            'lat' => $lat,
            'lng' => $lng,
            'title' => $row['title']
        );
    }
}

// JSON formatına çevir ve ekrana yazdır
echo json_encode($markers);

$conn->close();
?>
