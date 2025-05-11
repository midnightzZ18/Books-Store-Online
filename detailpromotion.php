<?php include 'condb.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Promotion</title>
   <!-- Bootstrap CSS -->
   <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container">
    <div class="row">
        <?php
        // เรียกใช้งานคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง promotions และเรียงลำดับตาม id จากมากไปน้อย
        $sql = "SELECT * FROM promotions ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        // ใช้ลูป while เพื่อวนลูปผ่านข้อมูลทั้งหมดที่ได้จากคำสั่ง SQL
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <div class="col-md-4">
                <br>
                <!-- แสดงรูปภาพของโปรโมชั่น -->
                <img src="img/<?= $row['image'] ?>" width="350px" class="mt-2 p-2 my-2 border" />
                <!-- แสดงข้อความด้านล่างของรูปภาพ -->
                <div class="text-center"> <!-- จัดข้อความตรงกลาง -->
                    <p><?= $row['promotion_name'] ?></p>
                    <p><?= $row['detail'] ?></p>
                </div>
            </div>
        <?php
        }
        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
        ?>
    </div>
</div>
</body>
</html>
