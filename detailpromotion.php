<?php 
    include 'condb.php'; 
    session_start();

    // รับค่า ID จาก URL ที่ส่งมาจากหน้า shop.php
    $promo_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    // ถ้าไม่มี ID ส่งมา ให้ดีดกลับหน้าแรก หรือเลือกอันล่าสุดมาแสดง
    if ($promo_id == "") {
        $sql = "SELECT * FROM promotions ORDER BY id DESC LIMIT 1";
    } else {
        $sql = "SELECT * FROM promotions WHERE id = '$promo_id'";
    }
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดโปรโมชั่น - Online Book Store</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background-color: #fffafa; font-family: 'Kanit', sans-serif; }
        
        .promo-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
            margin-top: 50px;
            border: 1px solid #ffe4e1;
        }

        .promo-img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .promo-title {
            color: #ad1457;
            font-weight: bold;
            margin-top: 20px;
            border-bottom: 2px solid #ffcccc;
            padding-bottom: 10px;
            display: inline-block;
        }

        .promo-detail {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-top: 20px;
        }

        .btn-back {
            background-color: #ff80ab;
            color: white;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: bold;
            transition: 0.3s;
            border: none;
            margin-top: 30px;
        }
        .btn-back:hover {
            background-color: #f50057;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="promo-container text-center">
                <?php if ($row) { ?>
                    <img src="img/<?= $row['image'] ?>" class="promo-img img-fluid" alt="Promotion Image" />
                    
                    <div class="content-section text-start px-md-4">
                        <h2 class="promo-title"><?= $row['promotion_name'] ?></h2>
                        <div class="promo-detail">
                            <i class="fas fa-info-circle me-2 text-pink"></i> 
                            <?= nl2br($row['detail']) ?>
                        </div>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">
                    
                    <a href="shop.php" class="btn btn-back shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> กลับไปเลือกหนังสือ
                    </a>
                <?php } else { ?>
                    <div class="py-5">
                        <h3 class="text-muted">ไม่พบข้อมูลโปรโมชั่น</h3>
                        <a href="shop.php" class="btn btn-back">กลับหน้าหลัก</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>