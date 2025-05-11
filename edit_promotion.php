<?php
    include 'condb.php';
    session_start();
    // ตรวจสอบการล็อกอิน
    if(!isset($_SESSION['admin_name'])){
        header('location:login_form.php');
    }

    // ตรวจสอบว่ามีการส่งค่า ID ของโปรโมชั่นมาหรือไม่
    if(isset($_GET['id'])) {
        $promotion_id = $_GET['id'];

        // ดึงข้อมูลโปรโมชั่นจากฐานข้อมูล
        $sql = "SELECT * FROM promotions WHERE id = $promotion_id";
        $result = mysqli_query($conn, $sql);
        $promotion = mysqli_fetch_assoc($result);

        if(!$promotion) {
            echo "ไม่พบโปรโมชั่นที่ต้องการแก้ไข";
            exit;
        }
    } else {
        echo "ไม่พบรหัสของโปรโมชั่น";
        exit;
    }

    // ตรวจสอบการส่งข้อมูลแบบ POST
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // นำเข้าข้อมูลที่แก้ไข
        $new_promotion_name = $_POST['promotion_name'];
        $new_promoStartDate = $_POST['promoStartDate'];
        $new_promoEndDate = $_POST['promoEndDate'];
        $new_detail = $_POST['detail'];

        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE promotions SET promotion_name = '$new_promotion_name', promoStartDate = '$new_promoStartDate', promoEndDate = '$new_promoEndDate', detail = '$new_detail' WHERE id = $promotion_id";

        if(mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "แก้ไขโปรโมชั่นเรียบร้อยแล้ว";
            header('Location: addpromotion.php');
            exit;
        } else {
            echo "มีข้อผิดพลาดเกิดขึ้นในการแก้ไขโปรโมชั่น: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Promotion</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <h2 class="text-center">แก้ไขโปรโมชั่น</h2>
        <!-- แสดงข้อความแสดงความผิดพลาด (ถ้ามี) -->
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="promotion_name">ชื่อโปรโมชั่น:</label>
                <input type="text" class="form-control" id="promotion_name" name="promotion_name" value="<?php echo $promotion['promotion_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="promoStartDate">วันที่เริ่มต้น:</label>
                <input type="date" class="form-control" id="promoStartDate" name="promoStartDate" value="<?php echo $promotion['promoStartDate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="promoEndDate">วันที่สิ้นสุด:</label>
                <input type="date" class="form-control" id="promoEndDate" name="promoEndDate" value="<?php echo $promotion['promoEndDate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="detail">รายละเอียด:</label>
                <textarea class="form-control" id="detail" name="detail" rows="3" required><?php echo $promotion['detail']; ?></textarea>
            </div><br>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
</body>
</html>
