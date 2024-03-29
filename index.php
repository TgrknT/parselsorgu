<?php
include "baglanti.php";

if (isset($_POST["ekle"])) {
    $ad = $_POST["name"];
    $soyad = $_POST["surname"];
    $yas = $_POST["age"];

    $ekle = $db->prepare("INSERT INTO hesaplar (AD, SOYAD, YAS) VALUES (:ad, :soyad, :yas)");
    $control = $ekle->execute(array(
        "ad" => $ad,
        "soyad" => $soyad,
        "yas" => $yas
    ));

    if ($control) {
        // Başarılı ekleme durumunda login sayfasına yönlendirme
        header("Location: index.php");
        exit;
    } else {
        echo "Eklenemedi";
    }
}

if (isset($_POST["giris"])) {
    $ad = $_POST["name"];
    $yas = $_POST["age"];

    $sorgu = $db->prepare("SELECT * FROM hesaplar WHERE AD = :ad AND YAS = :yas");
    $sorgu->execute(array(
        "ad" => $ad,
        "yas" => $yas
    ));

    if ($sorgu->rowCount() > 0) {
        // Giriş başarılı ise, istediğiniz sayfaya yönlendirebilirsiniz.
        header("Location: homepage.php");
        exit;
    } else {
        echo "Hatalı Giriş Bilgileri";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Parsel Sorgu</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Harici CSS dosyasını içeri al */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .container p {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span {
            font-size: 12px;
        }

        .container a {
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container button {
            background-color: orange;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button:hover {
            background-color: #00a1ff;
        }

        .container button.hidden {
            background-color: transparent;
            border-color: #fff;
        }

        .container form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in {
            transform: translateX(100%);
        }

        .sign-up {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        @keyframes move {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .social-icons {
            margin: 20px 0;
        }

        .social-icons a {
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle {
            background-color: linear-gradient(to left, #00a1ff, #00ff8f);
            height: 100%;
            background: linear-gradient(to right, #C33764, #1D2671);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container.active .toggle {
            transform: translateX(50%);
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-left {
            transform: translateX(-200%);
        }

        .container.active .toggle-left {
            transform: translateX(0);
        }

        .toggle-right {
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right {
            transform: translateX(200%);
        }
    </style>
    <title>Modern Login Page</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>HESAP OLUŞTUR</h1>

                <span>KAYIT OLMAK İÇİN BİLGİLERİNİZİ GİRİN</span>
                <input type="text" name="name" placeholder="İSİM">
                <input type="text" name="surname" placeholder="SOYİSİM">
                <input type="text" name="age" placeholder="YAŞ">
                <button type="submit" name="ekle">KAYIT OL</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>GİRİŞ YAP</h1>

                <input type="text" name="name" placeholder="İSİM">
                <input type="text" name="age" placeholder="YAŞ">  
                <a href="#">YAŞINI MI UNUTTUN?</a>
                <button type="submit" name="giris">GİRİŞ YAP</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Hesap Oluştur</h1>
                    <p></p>
                    <button class="hidden" id="login">GİRİŞ YAP</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Giriş Yap</h1>
                    <p>Bilgilerinizi Kullanıp Giriş Yapınız</p>
                    <button class="hidden" id="register">Kayıt Ol</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>
