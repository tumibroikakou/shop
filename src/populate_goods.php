<?php
include 'connect.php';

for ($i = 1; $i <= 100000; $i++) {
    $name = "Product $i";
    $alias = "product-$i";
    $article = "SKU" . str_pad($i, 6, '0', STR_PAD_LEFT);
    $price = rand(100, 10000) / 100;
    $quantity = rand(1, 100);
    $category_id = rand(1, 1000);
    $mysqli->query("INSERT INTO goods (name, alias, article, price, quantity, category_id) VALUES ('$name', '$alias', '$article', $price, $quantity, $category_id)");
}

$mysqli->close();
echo "done";
?>
