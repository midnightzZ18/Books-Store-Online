<?php
    include 'condb.php';
    session_start();
    // ตรวจสอบการล็อกอิน
    if(!isset($_SESSION['admin_name'])){
        header('location:login_form.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Promotions</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <h2 class="text-center">Manage Promotions</h2>
        <!-- แสดงข้อความแสดงความสำเร็จ (ถ้ามี) -->
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- รายการโปรโมชั่น -->
        <div class="row">
            <?php
            $sql = "SELECT * FROM promotions ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
            ?>
                <div class="col-md-4">
                    <br>
                    <img src="img/<?= $row['image'] ?>" width="350px" class="mt-2 p-2 my-2 border" />
                    <div class="text-center">
                        <p><?= $row['promotion_name'] ?></p>
                        <p><?= $row['detail'] ?></p>
                        <div>
                            <a href="edit_promotion.php?id=<?= $row['id'] ?>" class="btn btn-info">แก้ไข</a>
                            <a href="delete_promotion.php?id=<?= $row['id'] ?>" class="btn btn-danger">ลบ</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
