<?php
// เปิดการแสดง Error ทั้งหมดเพื่อหาสาเหตุ
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'condb.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. รับค่าและป้องกันการโดน SQL Injection
    $bookName    = mysqli_real_escape_string($conn, $_POST['bookName']);
    $authorName  = mysqli_real_escape_string($conn, $_POST['authorName']);
    $price       = $_POST['price'];
    $amount      = $_POST['amount'];  // แก้ไข: ตรวจสอบให้แน่ใจว่าชื่อฟิลด์ตรงกับฟอร์ม
    $type        = $_POST['type']; 
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // 2. จัดการรูปภาพ
    $filename = $_FILES["bookImage"]["name"];
    $tempname = $_FILES["bookImage"]["tmp_name"];
    $folder = "img/";

    // ตรวจสอบว่ามีโฟลเดอร์ img หรือไม่ ถ้าไม่มีให้สร้าง
    if (!is_dir($folder)) { mkdir($folder, 0777, true); }

    if ($filename != "") {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = "book_" . time() . "." . $ext; // ตั้งชื่อใหม่กันชื่อซ้ำ
        if (!move_uploaded_file($tempname, $folder . $new_filename)) {
            die("Error: ไม่สามารถอัปโหลดรูปภาพได้ ตรวจสอบสิทธิ์ Folder img");
        }
    } else {
        $new_filename = ""; // กรณีไม่ใส่รูป
    }

    // 3. บันทึกลงตาราง stocks
    // ลำดับคอลัมน์ตามฐานข้อมูล: book_id, book_name, amount, typebook_id, price, author_name, image, detail
    $sql = "INSERT INTO stocks (book_name, amount, typebook_id, price, author_name, image, detail) 
            VALUES ('$bookName', '$amount', '$type', '$price', '$authorName', '$new_filename', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('เพิ่มหนังสือสำเร็จ'); window.location='admin.php';</script>";
    } else {
        // ถ้าไม่เข้า จะโชว์ว่าพังเพราะอะไรตรงนี้
        die("Database Error: " . mysqli_error($conn) . "<br>SQL: " . $sql);
    }
}
?>