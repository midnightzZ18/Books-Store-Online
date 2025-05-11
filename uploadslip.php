<?php
session_start();
include 'condb.php'; 

// ข้อมูลจากฟอร์ม
$cus_name = $_POST['cus_name'];
$address = $_POST['address'];
$telephone = $_POST['telephone'];

// บันทึกข้อมูลลงในตาราง tb_order
$sql = "INSERT INTO tb_order (cus_name, address, telephone) VALUES ('$cus_name', '$address', '$telephone')";
if(mysqli_query($conn, $sql)) {
    $order_id = mysqli_insert_id($conn); // รหัสการสั่งซื้อที่เพิ่งสร้าง
    // อัปโหลดรูปภาพ
    $target_dir = "slip/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    // อัปเดตตาราง tb_order เพื่อเก็บที่อยู่ของไฟล์ภาพ
    $update_sql = "UPDATE tb_order SET image='$target_file' WHERE orderID='$order_id'";
    mysqli_query($conn, $update_sql);
    echo "บันทึกข้อมูลสำเร็จ";
    echo "window.location.href = 'print_order.php';";
} else {
    echo "มีข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
