<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proje";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $mapsLink = $_POST["mapsLink"];

    // Google Maps linkinden lat, lng ve başlık çekme
    $matches = [];
    preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $mapsLink, $matches);

    if (count($matches) === 3) {
        $lat = $matches[1];
        $lng = $matches[2];

        // Başlık ekleyebilirsiniz, isteğe bağlı
        $title = isset($_POST["title"]) ? $_POST["title"] : "";

        // Veritabanına yeni marker ekle
        $sql = "INSERT INTO markers (lat, lng, title) VALUES ('$lat', '$lng', '$title')";
        if ($conn->query($sql) === TRUE) {
            echo "Yeni marker başarıyla eklendi.";
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }

        // JavaScript ile sayfa yönlendirmesi
        echo '<script>window.location = "homepage.php";</script>';
    } else {
        echo "Geçerli bir Google Maps linki giriniz.";
    }
}

// Marker'ları çek
$sql_select = "SELECT lat, lng, title FROM markers";
$result = $conn->query($sql_select);

$markers = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = array(
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'title' => $row['title']
        );
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marker Ekleme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            color: #333;
        }

        .marker-list {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .marker-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Marker Ekleme</h1>
    </header>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Yeni Marker Ekle</h2>
        <label for="mapsLink">Google Maps Linki:</label>
        <input type="text" name="mapsLink" required>

        <label for="title">Başlık:</label>
        <input type="text" name="title" required>

        <button type="submit">Ekle</button>
    </form>

    <div class="marker-list">
        <h2>Mevcut Marker'lar</h2>
        <?php
        foreach ($markers as $marker) {
            echo '<div class="marker-item">';
            echo '<strong>Latitude:</strong> ' . $marker['lat'] . '<br>';
            echo '<strong>Longitude:</strong> ' . $marker['lng'] . '<br>';
            echo '<strong>Başlık:</strong> ' . $marker['title'];
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
