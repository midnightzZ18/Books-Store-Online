<?php
include 'condb.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า orderID มาหรือไม่
if(isset($_POST['orderID'])) {
    // รับค่า orderID จาก form
    $orderID = $_POST['orderID'];

    // อัปเดตสถานะการสั่งซื้อในฐานข้อมูล
    $sql = "UPDATE tb_order SET order_status = 0 WHERE orderID = $orderID";
    if ($conn->query($sql) === TRUE) {
        echo "สถานะการสั่งซื้อถูกเปลี่ยนเป็น ปฏิเสธ สำเร็จ";
        // Redirect ไปยังหน้า index.php
        echo "<script>window.location = 'confirm_order.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะการสั่งซื้อ: " . $conn->error;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
