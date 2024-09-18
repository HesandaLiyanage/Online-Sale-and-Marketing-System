<?php
session_start();
require_once('connect.php');


/* Check if admin is logged in
if ($_SESSION['role'] !== 'admin') {
    echo "<p>Access denied.</p>";
    exit();
}
*/
include "header.php";

// Fetch all users
$sql_users = "SELECT id, username_or_email, name, address, phone_number FROM users";
$result_users = $conn->query($sql_users);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <div class="admin-dashboard">
        <h1>Admin Dashboard</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_users->num_rows > 0) {
                    while ($user = $result_users->fetch_assoc()) {
                        echo "<tr>
                                <td>{$user['id']}</td>
                                <td>{$user['username_or_email']}</td>
                                <td>{$user['name']}</td>
                                <td>{$user['address']}</td>
                                <td>{$user['phone_number']}</td>
                                <td><a href='view_user.php?id={$user['id']}'>View Details</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
</html>
