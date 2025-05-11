<?php
include 'condb.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า orderID มาหรือไม่
if(isset($_POST['orderID'])) {
    // รับค่า orderID จาก form
    $orderID = $_POST['orderID'];

    // เริ่มต้นทำการอัปเดตข้อมูล
    $conn->begin_transaction();

    // อัปเดตสถานะการสั่งซื้อในตาราง tb_order
    $sql_order = "UPDATE tb_order SET order_status = 5 WHERE orderID = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $orderID);
    $stmt_order->execute();

    // ตรวจสอบว่าอัปเดตสถานะใน tb_order เสร็จสิ้นหรือไม่
    if ($stmt_order->affected_rows > 0) {
        // อัปเดตสถานะในตาราง history
        $sql_history = "UPDATE history SET order_status = 5 WHERE orderID = ?";
        $stmt_history = $conn->prepare($sql_history);
        $stmt_history->bind_param("i", $orderID);
        $stmt_history->execute();

        // ตรวจสอบว่าอัปเดตสถานะใน history เสร็จสิ้นหรือไม่
        if ($stmt_history->affected_rows > 0) {
            // ทำการ commit การเปลี่ยนแปลงทั้งหมด
            $conn->commit();
            echo "สถานะการสั่งซื้อถูกเปลี่ยนเป็น ปฏิเสธ สำเร็จ";
            // Redirect ไปยังหน้า index.php
            echo "<script>window.location = 'express.php';</script>";
        } else {
            // ถ้ามีปัญหาในการอัปเดตในตาราง history
            echo "เกิดข้อผิดพลาดในการอัปเดตสถานะการสั่งซื้อในตาราง history: " . $conn->error;
            // Rollback การเปลี่ยนแปลงทั้งหมด
            $conn->rollback();
        }
    } else {
        // ถ้ามีปัญหาในการอัปเดตในตาราง tb_order
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะการสั่งซื้อในตาราง tb_order: " . $conn->error;
        // Rollback การเปลี่ยนแปลงทั้งหมด
        $conn->rollback();
    }

    // ปิดคำสั่งที่เตรียมไว้
    $stmt_order->close();
    $stmt_history->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
