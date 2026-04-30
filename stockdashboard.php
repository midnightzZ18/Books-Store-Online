<?php 
    include 'condb.php';
    $sqlStocks = "SELECT s.book_id, s.image, s.book_Name, t.type_name, s.author_Name, s.price, s.amount 
                FROM stocks s
                INNER JOIN type t ON s.typebook_id = t.typebook_id";
    $resultStocks = mysqli_query($conn, $sqlStocks);

    $sqlHistory = "SELECT id, orderID, cus_name, sex, provinces, amphures, book_Name, orderQty, total_price 
                FROM history ORDER BY reg_date DESC";
    $resultHistory = mysqli_query($conn, $sqlHistory);

    // ดึงข้อมูลประเภทหนังสือ
    $sqlTypes = "SELECT * FROM type";
    $resultTypes = mysqli_query($conn, $sqlTypes);
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
    <link rel="icon" type="image/png" href="img/logo-web.png">
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
                        <a class="nav-link" href="data_userdashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-circle-user"></i></div>
                            ข้อมูลของผู้ใช้ (User)
                        </a>
                        <a class="nav-link" href="historydashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                            ประวัติการสั่งซื้อ
                        </a>
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
                            ตารางแสดงข้อมูลขาย
                        </div>
                        <div class="card-body">
                            <h2>รายการสินค้าในคลัง</h2>
                            <table id="datatablesStocks" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th></th>
                                        <th>ชื่อหนังสือ</th>
                                        <th>ประเภทหนังสือ</th>
                                        <th>ชื่อผู้แต่ง</th>
                                        <th>ราคา</th>
                                        <th>คงเหลือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_array($resultStocks)): ?>
                                        <tr>
                                            <td><?= $row['book_id'] ?></td>
                                            <td><img src='img/<?= $row['image'] ?>' class='rounded-img img-fluid' style='width: 80px; height: 100px;'></td>
                                            <td><?= $row['book_Name'] ?></td>
                                            <td><?= $row['type_name'] ?></td>
                                            <td><?= $row['author_Name'] ?></td>
                                            <td><?= $row['price'] ?></td>
                                            <td><?= $row['amount'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
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
<script>
    // JavaScript code for filtering rows based on selected type
    document.addEventListener("DOMContentLoaded", function() {
        var rows = document.querySelectorAll("#datatablesStocks tbody tr");
        rows.forEach(function(row) {
            row.style.display = "";
        });
    });
</script>
