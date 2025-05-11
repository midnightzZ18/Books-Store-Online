<?php
session_start();
include 'condb.php'; 

$cusName = $_POST['cus_name'];
$cusAddress = $_POST['cus_add'];
$cusTel = $_POST['cus_tel'];
$cusId = $_POST['cus_id'];
$cusProv = $_POST['cus_prov'];
$cusAmp = $_POST['cus_amp'];
$cusDist = $_POST['cus_dist'];
$finalPrice = $_SESSION["sum_price"];

// Check if a coupon is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['coupon_code'])) {
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
// Change variable name to 'sum_price' as used in retrieving data from session
$sql = "INSERT INTO tb_order (cus_name, address, telephone, total_price, order_status,cus_id,provinces,amphures,districts,coupon_price) 
        VALUES ('$cusName', '$cusAddress', '$cusTel', '" . $_SESSION["sum_price"] . "', '1','$cusId','$cusProv','$cusAmp','$cusDist','$finalPrice')";

if (mysqli_query($conn, $sql)) {
    $orderID = mysqli_insert_id($conn);
    $_SESSION["order_id"] = $orderID;

    for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
        if ($_SESSION["strProductID"][$i] != "") {
            $sql1 = "SELECT * FROM stocks WHERE book_id='" . $_SESSION["strProductID"][$i] . "'";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_array($result1);

            if ($row1['amount'] >= $_SESSION["strQty"][$i]) {
                $price = $row1['price'];
                $total = $_SESSION["strQty"][$i] * $price;

                $sql2 = "INSERT INTO order_detail(orderID, book_id, orderPrice, orderQty, Total)
                         VALUES ('$orderID', '" . $_SESSION["strProductID"][$i] . "', '$price', '" . $_SESSION["strQty"][$i] . "', '$total')";

                if (mysqli_query($conn, $sql2)) {
                    $sql3 = "UPDATE stocks SET amount = amount - '" . $_SESSION["strQty"][$i] . "'
                              WHERE book_id = '" . $_SESSION["strProductID"][$i] . "'";
                    if (mysqli_query($conn, $sql3)) {
                        echo "<script> window.location='print_order.php'; </script>";
                    } else {
                        echo "Error updating stock: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error inserting order detail: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('สินค้านี้ " . $_SESSION["strProductID"][$i] . " มีจำนวนไม่เพียงพอในสต็อก');";
                echo "window.location='cart.php';</script>";
                exit();
            }
        }
    }
    if(mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn); // รหัสการสั่งซื้อที่เพิ่งสร้าง
        // อัปโหลดรูปภาพ
        $target_dir = "";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        // อัปเดตตาราง tb_order เพื่อเก็บที่อยู่ของไฟล์ภาพ
        $update_sql = "UPDATE tb_order SET image='$target_file' WHERE orderID='$order_id'";
        mysqli_query($conn, $update_sql);
        echo "บันทึกข้อมูลสำเร็จ";
    } else {
        echo "มีข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
    }
    mysqli_close($conn);
    unset($_SESSION["intLine"]);
    unset($_SESSION["strProductID"]);
    unset($_SESSION["strQty"]);
    unset($_SESSION["sum_price"]);
    
} else {
    echo "Error inserting order: " . mysqli_error($conn);
}
?>
