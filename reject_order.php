<?php
include 'condb.php';
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
    exit;
}

if (isset($_GET['id'])) {
    $orderID = intval($_GET['id']);
    
    // อัปเดตสถานะเป็น 0 (ปฏิเสธ/ยกเลิก)
    $sql = "UPDATE tb_order SET order_status = '0' WHERE orderID = '$orderID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('ปฏิเสธออเดอร์เรียบร้อยแล้ว'); window.location='confirm_order.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล'); window.history.back();</script>";
    }
}
?>