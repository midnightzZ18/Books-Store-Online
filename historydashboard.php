<?php 
    include 'condb.php';
    $sql = "SELECT id, orderID, cus_name, sex, provinces, amphures, book_Name, orderQty, total_price FROM history ORDER BY reg_date DESC";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Hisroty</title>
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
                                <span style="font-size: 20px;">ตารางแสดงข้อมูลการขาย</span>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ลำดับ</th>
                                            <th>รหัสการสั่งซื้อ</th>
                                            <th>ชื่อ - นามสกุล</th>
                                            <th>เพศ</th>
                                            <th>จังหวัด</th>
                                            <th>อำเภอ</th>
                                            <th>ชื่อหนังสือ</th>
                                            <th>จำนวน</th>
                                            <th>ยอดรวม</th>
                                        </tr>
                                    </thead>
                                    <?php while($row = mysqli_fetch_array($result)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['orderID'] ?></td>
                                        <td><?= $row['cus_name'] ?></td>
                                        <td><?= $row['sex'] ?></td>
                                        <td><?= $row['provinces'] ?></td>
                                        <td><?= $row['amphures'] ?></td>
                                        <td><?= $row['book_Name'] ?></td>
                                        <td><?= $row['orderQty'] ?></td>
                                        <td><?= $row['total_price'] ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
   </body>
</html>

<?php mysqli_close($conn);?> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

