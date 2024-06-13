<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");

    if ($handle !== FALSE) {
        // пропустить первую строку
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = $mysqli->real_escape_string($data[0]);
            $alias = $mysqli->real_escape_string($data[1]);
            $article = $mysqli->real_escape_string($data[2]);
            $price = (float) $data[3];
            $quantity = (int) $data[4];
            $category_id = (int) $data[5];
            $photo_url = $mysqli->real_escape_string($data[6]);

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

        fclose($handle);
        echo "Products imported successfully!";
    } else {
        echo "Error opening the file.";
    }
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
        <input type="file" name="csv_file" accept=".csv" />
        <button type="submit">Import</button>
    </form>
    <a href="admin.php">Back to Admin</a>
</body>

</html>
