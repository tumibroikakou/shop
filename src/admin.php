<?php
include 'connect.php';
include 'pagination.php';

// навигация и сортировка
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9;
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class='container space-evenly'>
    <section>
        <?php
        echo "<table class='product-table'>
            <tr>
                <th><a href='?sort=name&order=".($sortOrder == 'ASC' ? 'DESC' : 'ASC')."'>Name ▼▲</a></th>
                <th><a href='?sort=category_name&order=".($sortOrder == 'ASC' ? 'DESC' : 'ASC')."'>Category ▼▲</a></th>
                <th><a href='?sort=price&order=".($sortOrder == 'ASC' ? 'DESC' : 'ASC')."'>Price ▼▲</a></th>
                <th><a href='?sort=quantity&order=".($sortOrder == 'ASC' ? 'DESC' : 'ASC')."'>Quantity ▼▲</a></th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['category_name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['quantity']}</td>
                <td><img src='{$row['photo_url']}' width='50'></td>
                <td class='product-actions'>
                    <form method='post'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='submit' name='edit' value='Edit'>
                        <input type='submit' name='delete' value='Delete'>
                    </form>
                </td>
            </tr>";
        }

        echo "</table>";
        ?>
            <div class="pagination">
                <?php pagination($perPage, $page, $totalProducts); ?>
            </div>
    </section>


    <section class='flex column space-between'>
    <?php
    // круд
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $mysqli->query("DELETE FROM goods_photos WHERE goods_id=$id");
            $mysqli->query("DELETE FROM goods WHERE id=$id");

            // header("Location: admin.php?page=$page");
            // exit;
        } elseif (isset($_POST['edit'])) {
            $id = $_POST['id'];
            $product = $mysqli->query("SELECT * FROM goods WHERE id=$id")->fetch_assoc();
            $photo = $mysqli->query("SELECT photo_url FROM goods_photos WHERE goods_id=$id")->fetch_assoc();
            ?>
            <form method="post" enctype="multipart/form-data">
                <h2>Edit Form</h2>
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                Name: <input type="text" name="name" value="<?php echo $product['name']; ?>"><br>
                Alias: <input type="text" name="alias" value="<?php echo $product['alias']; ?>"><br>
                Article: <input type="text" name="article" value="<?php echo $product['article']; ?>"><br>
                Price: <input type="text" name="price" value="<?php echo $product['price']; ?>"><br>
                Quantity: <input type="text" name="quantity" value="<?php echo $product['quantity']; ?>"><br>
                Category ID: <input type="text" name="category_id" value="<?php echo $product['category_id']; ?>"><br>
                Current Photo: <img src="<?php echo $photo['photo_url']; ?>" width="50"><br>
                New Photo: <input type="file" name="photo"><br>
                or Photo URL: <input type="text" name="photo_url" value=""><br>
                <input type="submit" name="update" value="Update">
            </form>
            <?php
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $alias = $_POST['alias'];
            $article = $_POST['article'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $category_id = $_POST['category_id'];

            $mysqli->query("UPDATE goods SET name='$name', alias='$alias', article='$article', price=$price, quantity=$quantity, category_id=$category_id WHERE id=$id");

            if (!empty($_FILES['photo']['name'])) {
                $photo_path = 'uploads/' . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
                $photo_url = $mysqli->real_escape_string($photo_path);
                $mysqli->query("DELETE FROM goods_photos WHERE goods_id=$id");
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($id, '$photo_url')");
            } elseif (!empty($_POST['photo_url'])) {
                $photo_url = $mysqli->real_escape_string($_POST['photo_url']);
                $mysqli->query("DELETE FROM goods_photos WHERE goods_id=$id");
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($id, '$photo_url')");
            }

            // header("Location: admin.php?page=$page");
            // exit;
        } elseif (isset($_POST['create'])) {
            $name = $_POST['name'];
            $alias = $_POST['alias'];
            $article = $_POST['article'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $category_id = $_POST['category_id'];

            $mysqli->query("INSERT INTO goods (name, alias, article, price, quantity, category_id) VALUES ('$name', '$alias', '$article', $price, $quantity, $category_id)");
            $product_id = $mysqli->insert_id;

            if (!empty($_FILES['photo']['name'])) {
                $photo_path = 'uploads/' . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
                $photo_url = $mysqli->real_escape_string($photo_path);
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($product_id, '$photo_url')");
            } elseif (!empty($_POST['photo_url'])) {
                $photo_url = $mysqli->real_escape_string($_POST['photo_url']);
                $mysqli->query("INSERT INTO goods_photos (goods_id, photo_url) VALUES ($product_id, '$photo_url')");
            }

            // header("Location: admin.php?page=$page");
            // exit;
        }
    }
    ?>
    <div class='form_container'>
        <form method="post" enctype="multipart/form-data">
            <h2>Create New Product</h2>
            Name: <input type="text" name="name"><br>
            Alias: <input type="text" name="alias"><br>
            Article: <input type="text" name="article"><br>
            Price: <input type="text" name="price"><br>
            Quantity: <input type="text" name="quantity"><br>
            Category ID: <input type="text" name="category_id"><br>
            Photo: <input type="file" name="photo"><br>
            or Photo URL: <input type="text" name="photo_url" value=""><br>
            <input type="submit" name="create" value="Create">
        </form>
        <a href="import_csv.php">Import Products from Excel</a>
    </div>
    </section>
    </main>
</body>
</html>

