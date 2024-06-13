<?php
include 'connect.php';

for ($i = 1; $i <= 1000; $i++) {
    $name = "Category $i";
    $alias = "category-$i";
    $mysqli->query("INSERT INTO goods_category (name, alias) VALUES ('$name', '$alias')");
}

$mysqli->close();
echo "done";
?>
