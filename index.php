<?php
include 'src/connect.php';
include 'src/pagination.php';

// навигация и сортировка
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalProducts = $mysqli->query("SELECT COUNT(*) AS count FROM goods")->fetch_assoc()['count'];
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$query = "SELECT goods.id, goods.name, goods.price, goods.quantity, goods_category.name AS category_name, goods_photos.photo_url
        FROM goods
        JOIN goods_category ON goods.category_id = goods_category.id
        LEFT JOIN goods_photos ON goods.id = goods_photos.goods_id
        GROUP BY goods.id
        ORDER BY $sortField $sortOrder
        LIMIT $offset, $perPage";
$result = $mysqli->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body>
    <div class="admin-link">
        <a href="src/admin.php">Admin Panel</a>
    </div>
    <section class="container">
        <?php
        // карточки
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='{$row['photo_url']}' alt='{$row['name']}'>";
            echo "<h3>{$row['name']}</h3>";
            echo "<p>Price: {$row['price']}$</p>";
            echo "<p>Quantity: {$row['quantity']}pcs</p>";
            echo "<p>Category: {$row['category_name']}</p>";
            echo "</div>";
        }
        ?>
    </section>

    <!-- навигация -->
    <div class="pagination">
        <?php pagination($perPage, $page, $totalProducts); ?>
    </div>
</body>

</html>
