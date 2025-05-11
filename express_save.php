<?php
session_start();

// Include your database connection file
include 'condb.php'; // Assuming you have a file named db_connection.php for database connection


// Check if orderID is provided via URL
if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Use prepared statements
    $stmt = $conn->prepare("SELECT id, name FROM delivery_form WHERE user_name = ?");
    $stmt->bind_param("s", $_SESSION['user_name']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_delivery = $row['id'];
        $name_delivery = $row['name'];

        // Update history table with id_delivery and name_delivery
        $update_stmt = $conn->prepare("UPDATE history SET id_delivery = ?, name_delivery = ? WHERE orderID = ?");
        $update_stmt->bind_param("isi", $id_delivery, $name_delivery, $orderID);
        $update_stmt->execute();

     // Check if the update was successful
     if ($update_stmt->affected_rows > 0) {
        // Redirect to express.php
        header("Location: express.php");
        exit(); // Ensure script execution stops after redirection
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }
    $update_stmt->close();
} else {
    echo "ไม่พบข้อมูลผู้ส่ง";
}
$stmt->close();
} else {
echo "ไม่ได้ระบุเลขออเดอร์";
}
?>
