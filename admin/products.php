<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role']) === 'admin') {
    echo "You aren't an admin!!!";
    exit();
}

include "../db_connect.php";

// Initialize variables
$products = [];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;



// Fetch all products
$sql = "SELECT products.id, products.name AS product_name, products.price, products.stock_quantity, 
               categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id";
$result = $conn->query($sql);

if ($result === false) {
    die("Error in query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this product?');
    }
    </script>
    <link rel="stylesheet" href="../css/products.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Manage Products</h1>
    </header>

    <!-- Product List -->
    <div class="product-table">
        <div class="btn-add-new">
            <a href="add_product.php" class="btn btn-add">Add New Product</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td><?php echo htmlspecialchars("$" . number_format($product['price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirmDelete();">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
