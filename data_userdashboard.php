<?php
    include 'condb.php';
    $sql = "SELECT u.id, u.name, u.email, u.user_name, u.phone_number, u.sex, a.name_th AS amphur_name, p.name_th AS province_name
            FROM user_form u
            LEFT JOIN amphures a ON u.Ref_dist_id = a.id
            LEFT JOIN provinces p ON u.Ref_prov_id = p.id";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <!-- เพิ่ม stylesheet หรือ CSS ต่างๆ ตามความเหมาะสม -->
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
                            <span style="font-size: 20px;">ตารางแสดงข้อมูลของผู้ใช้ (User)</span>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ชื่อ - นามสกุล</th>
                                        <th>Email</th>
                                        <th>User Name</th>
                                        <th>เบอร์โทรศัพท์</th>
                                        <th>เพศ</th>
                                        <th>อำเภอ</th>
                                        <th>จังหวัด</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['id'] . "</td>";
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td>" . $row['email'] . "</td>";
                                                echo "<td>" . $row['user_name'] . "</td>";
                                                echo "<td>" . $row['phone_number'] . "</td>";
                                                echo "<td>" . $row['sex'] . "</td>";
                                                echo "<td>" . $row['amphur_name'] . "</td>";
                                                echo "<td>" . $row['province_name'] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='13'>No records found</td></tr>";
                                        }
                                    ?>
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
<?php mysqli_close($conn); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
