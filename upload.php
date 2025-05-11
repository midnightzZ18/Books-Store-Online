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

    // Loop through each uploaded file
    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        $target_dir = "slip/";
        $target_file = $target_dir . basename($_FILES["images"]["name"][$key]);
        
        // Move each uploaded file to the target directory
        move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file);

        // Insert file path into the database
        $insert_image_sql = "INSERT INTO tb_order_images (orderID, image_path) VALUES ('$order_id', '$target_file')";
        mysqli_query($conn, $insert_image_sql);
    }

    // แสดงหน้าต่างแจ้งเตือนและกลับไปยังหน้าเดิม
    echo "<script>";
    echo "alert('บันทึกข้อมูลสำเร็จ');";
    echo "window.location.href = 'print_order.php';";
    echo "</script>";
} else {
    echo "มีข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
