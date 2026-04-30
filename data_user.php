<?php
    @include 'condb.php';
    session_start();

    if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
    }

    // --- ระบบแบ่งหน้า ---
    $limit = 15;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // นับจำนวนผู้ใช้ทั้งหมด
    $res_count = $conn->query("SELECT COUNT(*) as total FROM user_form");
    $total_row = $res_count->fetch_assoc();
    $total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ใช้งาน</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h1>ข้อมูลผู้ใช้งาน</h1>
        </div>

        <?php
        // แก้ไข JOIN ให้ตรงกับชื่อคอลัมน์จริงใน database ของคุณ
        $sql = "SELECT uf.name, uf.email, uf.sex, uf.address, uf.phone_number, uf.id, 
                       p.name_th AS province_name, 
                       a.name_th AS amphur_name, 
                       d.name_th AS district_name
                FROM user_form uf
                LEFT JOIN provinces p ON uf.Ref_prov_id = p.id
                LEFT JOIN amphures a ON uf.Ref_dist_id = a.id
                LEFT JOIN districts d ON uf.Ref_subdist_id = d.id
                ORDER BY uf.id DESC
                LIMIT $limit OFFSET $offset"; 
        
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<div class='table-responsive shadow-sm'>";
            echo "<table class='table table-hover align-middle bg-white'>";
            echo "<thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>เพศ</th>
                        <th>E-mail</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>จังหวัด</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><span class='badge bg-secondary'>".$row["id"]."</span></td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["sex"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["phone_number"]."</td>";
                echo "<td>".($row["province_name"] ?: '-')."</td>";
                echo "</tr>";
            }
            echo "</tbody></table></div>";

            // เรียกใช้ฟังก์ชันแสดงปุ่มหน้า
            renderPagination($page, $total_pages);

        } else {
            echo "<div class='text-center py-5 text-muted'>ไม่พบข้อมูลผู้ใช้งาน</div>";
        }
        $conn->close();
        ?>
    </div>

<?php
// ฟังก์ชัน Pagination (วางไว้ในไฟล์เพื่อป้องกัน Error)
function renderPagination($page, $total_pages) {
    if ($total_pages <= 1) return;
    echo '<nav class="mt-4"><ul class="pagination justify-content-center">';
    $prev = $page - 1;
    echo '<li class="page-item '.($page <= 1 ? 'disabled':'').'"><a class="page-link" href="?page='.$prev.'">ก่อนหน้า</a></li>';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<li class="page-item '.($page == $i ? 'active':'').'"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
    }
    $next = $page + 1;
    echo '<li class="page-item '.($page >= $total_pages ? 'disabled':'').'"><a class="page-link" href="?page='.$next.'">ถัดไป</a></li>';
    echo '</ul></nav>';
}
?>
</body>
</html>