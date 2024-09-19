<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "You aren't an admin!!!";
    exit();
}

include "../db_connect.php";

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

// Fetch the product data based on the ID
if ($product_id) {
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    // Ensure values are quoted and properly formatted
    $name = $conn->real_escape_string($name);
    $price = floatval($price);
    $stock_quantity = intval($stock_quantity);

    $update_sql = "UPDATE products SET name = '$name', price = $price, stock_quantity = $stock_quantity WHERE id = $product_id";
    $update_stmt = $conn->prepare($update_sql);

    if ($update_stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $update_stmt->execute();

    if ($update_stmt->errno) {
        die('Execute failed: ' . $update_stmt->error);
    }

    $update_stmt->close();

    header("Location: products.php"); // Redirect back to the product management page
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<link rel="stylesheet" href="../css/edit_product.css">
<body>
    <h1>Edit Product</h1>
    <?php if ($product): ?>
        <form method="POST" action="">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required><br>

            <button type="submit">Update Product</button>
        </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</body>
</html>
