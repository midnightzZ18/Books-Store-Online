<?php
session_start(); // Start session
include 'condb.php'; // Include database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $cusName = $_POST['cus_name'];
    $cusAddress = $_POST['cus_add'];
    $cusTel = $_POST['cus_tel'];
    $cusId = $_POST['cus_id'];
    $cusProv = $_POST['cus_prov'];
    $cusAmp = $_POST['cus_amp'];
    $cusDist = $_POST['cus_dist'];

    // Initialize final price with the sum price
    $finalPrice = $_SESSION["sum_price"];

    // Check if a coupon is submitted
    if (isset($_POST['coupon_code'])) {
        $couponCode = $_POST['coupon_code'];
        if (!empty($couponCode)) {
            // Fetch coupon data from database
            $sql_coupon = "SELECT * FROM coupons WHERE coupon_code = ?";
            $stmt = $conn->prepare($sql_coupon);
            $stmt->bind_param("s", $couponCode);
            $stmt->execute();
            $result_coupon = $stmt->get_result();
            
            if ($row_coupon = $result_coupon->fetch_assoc()) {
                // Check if the coupon is active
                if ($row_coupon['status'] == 1) {
                    $discount = $row_coupon['discount'];
                    // Apply discount to final price
                    $finalPrice = $_SESSION["sum_price"] - $discount;
                } else {
                    echo "คูปองไม่สามารถใช้งานได้"; // Coupon cannot be used
                }
            } else {
                echo "คูปองไม่ถูกต้องหรือหมดอายุ"; // Invalid or expired coupon
            }
        }
    }

    // Insert order into database
    $sql = "INSERT INTO tb_order (cus_name, address, telephone, total_price, order_status, cus_id, provinces, amphures, districts, coupon_price) 
            VALUES (?, ?, ?, ?, '1', ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $cusName, $cusAddress, $cusTel, $_SESSION["sum_price"], $cusId, $cusProv, $cusAmp, $cusDist, $finalPrice);

    if ($stmt->execute()) {
        $orderID = $stmt->insert_id; // Get the inserted order ID
        $_SESSION["order_id"] = $orderID; // Store order ID in session

        // Insert order details into database
        for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
            if ($_SESSION["strProductID"][$i] != "") {
                // Retrieve product information
                $sql1 = "SELECT * FROM stocks WHERE book_id=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("s", $_SESSION["strProductID"][$i]);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $row1 = $result1->fetch_assoc();

                $price = $row1['price'];
                $total = $_SESSION["strQty"][$i] * $price;

                // Insert order detail
                $sql2 = "INSERT INTO order_detail (orderID, book_id, orderPrice, orderQty, Total)
                         VALUES (?, ?, ?, ?, ?)";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("sssss", $orderID, $_SESSION["strProductID"][$i], $price, $_SESSION["strQty"][$i], $total);

                if ($stmt2->execute()) {
                    // Update stock quantity
                    $sql3 = "UPDATE stocks SET amount = amount - ?
                              WHERE book_id = ?";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param("ss", $_SESSION["strQty"][$i], $_SESSION["strProductID"][$i]);

                    if ($stmt3->execute()) {
                        // Redirect to print order page
                        echo "<script> window.location='print_order.php'; </script>";
                    } else {
                        echo "Error updating stock: " . $conn->error;
                    }
                } else {
                    echo "Error inserting order detail: " . $conn->error;
                }
            }
        }

        // Upload image
        $target_dir = "";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Update order table to store image address
        $update_sql = "UPDATE tb_order SET image=? WHERE orderID=?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ss", $target_file, $orderID);
        $stmt_update->execute();

        echo "บันทึกข้อมูลสำเร็จ";
    } else {
        echo "มีข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
    }

    $stmt->close(); // Close statement
    mysqli_close($conn); // Close database connection
    unset($_SESSION["intLine"]); // Unset session variables
    unset($_SESSION["strProductID"]);
    unset($_SESSION["strQty"]);
    unset($_SESSION["sum_price"]);
} else {
    echo "เกิดข้อผิดพลาดในการส่งข้อมูล"; // Error in form submission
}
?>
