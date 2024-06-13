<?php
include 'src/connect.php';
include 'src/pagination.php';

// навигация и сортировка
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8;
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
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product {
            width: 200px;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product:hover {
            transform: translateY(-10px);
        }

        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            background-color: #f4f4f4;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }

        .pagination input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-left: 10px;
        }

        .pagination form {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="admin-link">
        <a href="src/admin.php">Admin Panel</a>
    </div>
    <div class="container">
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
    </div>

    <!-- навигация -->
    <div class="pagination">
        <?php pagination($perPage, $page, $totalProducts); ?>
    </div>
</body>

</html>
