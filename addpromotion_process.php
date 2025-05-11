<?php
    include 'condb.php';
    session_start();

    // Check if user is logged in as admin
    if(!isset($_SESSION['admin_name'])){
        header('location:login_form.php');
        exit;
    }

    // Check if form data is submitted
    if(isset($_POST['promotion_name']) && isset($_POST['promoStartDate']) && isset($_POST['promoEndDate']) && isset($_POST['detail']) && isset($_FILES['image'])) {
        $promotion_name = $_POST['promotion_name'];
        $promoStartDate = $_POST['promoStartDate'];
        $promoEndDate = $_POST['promoEndDate'];
        $detail = $_POST['detail'];
        $image = $_FILES['image']['name'];
        $temp_image = $_FILES['image']['tmp_name'];

        // Move uploaded image to folder
        move_uploaded_file($temp_image, "img/$image");

        // SQL query to insert promotion data into database
        $sql = "INSERT INTO promotions (promotion_name, promoStartDate, promoEndDate, detail, image) VALUES ('$promotion_name', '$promoStartDate', '$promoEndDate', '$detail', '$image')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to addpromotion.php with success message
            $_SESSION['success_message'] = "โปรโมชั่นถูกเพิ่มเรียบร้อยแล้ว";
            header('location:addpromotion.php');
            exit;
        } else {
            // Redirect to addpromotion.php with error message if query fails
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการเพิ่มโปรโมชั่น: " . $conn->error;
            header('location:addpromotion.php');
            exit;
        }
    } else {
        // Redirect to addpromotion.php if form data is not submitted properly
        $_SESSION['error_message'] = "ข้อมูลไม่ถูกต้องหรือไม่ครบถ้วน";
        header('location:addpromotion.php');
        exit;
    }

    // Close database connection
    $conn->close();
?>
