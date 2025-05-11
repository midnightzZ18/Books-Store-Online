<?php
    include 'condb.php';

    // เช็คว่ามีการส่งค่า book_id มาหรือไม่
    if(isset($_GET['id'])) {
        // รับค่า book_id และทำความสะอาดข้อมูล
        $book_id = intval($_GET['id']);

        // เตรียมคำสั่ง SQL สำหรับลบข้อมูลโดยใช้ prepared statement
        $sql = "DELETE FROM stocks WHERE book_id = ?";

        // เตรียม statement
        $stmt = mysqli_prepare($conn, $sql);
        
        // ผูกค่า parameter
        mysqli_stmt_bind_param($stmt, 'i', $book_id);

        // ทำการ execute statement
        mysqli_stmt_execute($stmt);

        // ปิด statement
        mysqli_stmt_close($stmt);

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);

        //  Redirect กลับไปยังหน้าเดิมหลังจากลบข้อมูลเสร็จ
        echo "<script>window.location.href = 'admin.php';</script>";
    } else {
        // ถ้าไม่มี book_id ให้ Redirect กลับไปยังหน้าเดิม
        echo "<script>window.location.href = 'admin.php';</script>";
    }
?>
