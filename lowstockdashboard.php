<?php 
    include 'condb.php';
    // Check if there are less than 10 books left in stock
    $sql_alert = "SELECT COUNT(*) AS low_stock_count FROM stocks WHERE amount < 10";
    $result_alert = $conn->query($sql_alert);
    $row_alert = $result_alert->fetch_assoc();
    $low_stock_count = $row_alert['low_stock_count'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>คลังสินค้าและประวัติการสั่งซื้อ</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'menu1.php';?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="stockdashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box-archive"></i></div>
                                คลังสินค้า
                            </a>
                            <a class="nav-link" href="lowstockdashboard.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-square-xmark"></i></div>
                                สินค้าใกล้หมด
                            </a>
                            <a class="nav-link" href="data_userdashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-circle-user"></i></div>
                                ข้อมูลของผู้ใช้ (User)
                            </a>
                            <a class="nav-link" href="historydashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                                ประวัติการขาย
                            </a>
                            <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                ประเภทหนังสือ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="self_dev.php">พัฒนาตนเอง</a>
                                    <a class="nav-link" href="literature.php">วรรณกรรม</a>
                                    <a class="nav-link" href="Food_health.php">อาหาร/สุขภาพ</a>
                                    <a class="nav-link" href="Entertain_travel.php">บันเทิง/ท่องเที่ยว</a>
                                    <a class="nav-link" href="anime.php">การ์ตูน/มังงะ</a>
                                </nav>
                            </div> -->
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                กราฟข้อมูล
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">    
                        <div class="card mb-4 mt-4">
                            <div class="card-header">
                                <i class="fas fa-table me-5"></i>
                                <span style="font-size: 20px;">สินค้าใกล้หมด</span>
                                <?php 
                                    // Show alert if there are less than 10 books left in stock
                                    if ($low_stock_count > 0) {
                                        echo "<p>มีหนังสือที่มีจำนวนน้อยกว่า 10 ฉบับ: $low_stock_count หนังสือ</p>";
                                    }
                                ?>
                            </div>
                            <?php
                                $sql = "SELECT s.book_id, s.image, s.book_Name, t.type_name, s.author_Name, s.price, s.amount 
                                        FROM stocks s
                                        INNER JOIN type t ON s.typebook_id = t.typebook_id
                                        WHERE s.amount < 10"; // Filter only books with less than 10 in stock
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<table class='table table-striped'>";
                                    echo "<tr><th>ลำดับ</th><th></th><th>ชื่อหนังสือ</th><th>ประเภทหนังสือ</th><th>ชื่อผู้แต่ง</th><th>ราคา</th><th>คงเหลือ</th></tr>";
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>".$row["book_id"]."</td>";
                                        // แสดงรูปภาพ
                                        echo "<td><img src='img/".$row['image']."' class='rounded-img img-fluid' style='width: 80px; height: 100px;'></td>";
                                        echo "<td>".$row["book_Name"]."</td>";
                                        echo "<td>".$row["type_name"]."</td>";
                                        echo "<td>".$row["author_Name"]."</td>";
                                        echo "<td>".$row["price"]."</td>";
                                        echo "<td>".$row["amount"]."</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                $conn->close();
                            ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
