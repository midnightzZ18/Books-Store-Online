<?php
include 'condb.php';

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าที่ส่งมาจากฟอร์ม
    $bookName = $_POST['bookName'];
    $quantity = $_POST['quantity'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $authorName = $_POST['authorName'];
    $description = $_POST['description'];

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if(isset($_FILES['bookImage']) && $_FILES['bookImage']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['bookImage']['name'];
        $image_tmp = $_FILES['bookImage']['tmp_name'];
        // เลือกโฟลเดอร์ที่ต้องการบันทึกไฟล์รูปภาพ
        $target_dir = "img/";
        move_uploaded_file($image_tmp, $target_dir . $image);
    } else {
        // ถ้าไม่มีการอัปโหลดรูปภาพ ให้ใช้รูปภาพเริ่มต้น
        $image = "default_image.jpg"; // แทนที่ชื่อรูปภาพเริ่มต้นที่คุณต้องการ
    }

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO stocks (book_Name, amount, typebook_id, price, author_Name, image, detail) 
            VALUES ('$bookName', '$quantity', '$type', '$price', '$authorName', '$image', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "เพิ่มสินค้า \"$bookName\" เรียบร้อยแล้ว";
        echo "<script>window.location.href = 'admin.php';</script>"; // เด้งกลับไปที่หน้า admin.php
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
