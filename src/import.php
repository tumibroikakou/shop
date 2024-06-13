<?php
include 'connect.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // пропустить первую строку
    array_shift($rows);

    foreach ($rows as $row) {
        $name = $mysqli->real_escape_string($row[0]);
        $alias = $mysqli->real_escape_string($row[1]);
        $article = $mysqli->real_escape_string($row[2]);
        $price = (float)$row[3];
        $quantity = (int)$row[4];
        $category_id = (int)$row[5];
        $photo_url = $mysqli->real_escape_string($row[6]);

        $existing_product = $mysqli->query("SELECT id FROM goods WHERE article='$article'");
        if ($existing_product->num_rows > 0) {
            $product_id = $existing_product->fetch_assoc()['id'];
            $mysqli->query("UPDATE goods SET name='$name', alias='$alias', price=$price, quantity=$quantity, category_id=$category_id WHERE article='$article'");

            if ($photo_url) {
                $mysqli->query("DELETE FROM goods_photos WHERE goods_id=$product_id");
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($product_id, '$photo_url')");
            }
        } else {
            $mysqli->query("INSERT INTO goods (name, alias, article, price, quantity, category_id)
                            VALUES ('$name', '$alias', '$article', $price, $quantity, $category_id)");
            $product_id = $mysqli->insert_id;

            if ($photo_url) {
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($product_id, '$photo_url')");
            }
        }
    }

    echo "Products imported successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Products</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" accept=".xlsx, .xls" />
        <button type="submit">Import</button>
    </form>
    <a href="admin.php">Back to Admin</a>
</body>
</html>
