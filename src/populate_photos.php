// shop/src/populate_photos.php

<?php
include 'connect.php';

// пик рандом ФОТО из массива (фулл папка)
function getRandomPhotoUrl($imgDir) {
    $images = glob($imgDir . '/*.{jpg,png,gif}', GLOB_BRACE);
    if (!$images) {
        return false;
    }
    $randomImage = $images[array_rand($images)];
    return basename($randomImage);
}

$imgDir = __DIR__ . '/img'; // директория с фото
$basePhotoUrl = 'http://localhost/shop/src/img/'; // Base URL для фоток (локалхост=))

for ($i = 1; $i <= 100000; $i++) {
    for ($j = 1; $j <= 3; $j++) {
        $photoFilename = getRandomPhotoUrl($imgDir);
        if ($photoFilename) {
            $photoUrl = $basePhotoUrl . $photoFilename;
            $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($i, '$photoUrl')");
        } else {
            echo "No images found in the directory.";
            exit;
        }
    }
}

$mysqli->close();
?>
