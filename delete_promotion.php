<?php
    include 'condb.php';
    session_start();
    // ตรวจสอบการล็อกอิน
    if(!isset($_SESSION['admin_name'])){
        header('location:login_form.php');
    }

    // ตรวจสอบการส่งค่า ID ของโปรโมชั่นมา
    if(isset($_GET['id'])) {
        $promotion_id = $_GET['id'];

        // ลบโปรโมชั่นจากฐานข้อมูล
        $sql = "DELETE FROM promotions WHERE id = $promotion_id";

        if(mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "ลบโปรโมชั่นเรียบร้อยแล้ว";
            header('Location: addpromotion.php');
            exit;
        } else {
            echo "มีข้อผิดพลาดเกิดขึ้นในการลบโปรโมชั่น: " . mysqli_error($conn);
        }
    } else {
        echo "ไม่พบรหัสของโปรโมชั่น";
        exit;
    }
?>
