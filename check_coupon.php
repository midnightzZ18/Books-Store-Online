<?php
include 'condb.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["coupon_code"])) {
        $couponCode = $_POST["coupon_code"];

        // Query เพื่อตรวจสอบคูปอง
        $sql = "SELECT * FROM coupons WHERE coupon_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $couponCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // คูปองถูกต้อง
            echo "valid";
        } else {
            // คูปองไม่ถูกต้องหรือหมดอายุ
            echo "invalid";
        }
    }
}
?>
