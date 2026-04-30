<?php
include 'condb.php';
session_start();

if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
    header('location:login_form.php');
    exit;
}

// --- เริ่มต้นระบบแบ่งหน้า ---
$limit = 15; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$result_count = mysqli_query($conn, "SELECT COUNT(id) AS total FROM history");
$row_count = mysqli_fetch_assoc($result_count);
$total_pages = ceil($row_count['total'] / $limit);
// -----------------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'adminmenu.php'; ?>
<div class="container mt-5">
    <div class="alert alert-warning text-center" role="alert">
        <h1>ประวัติการขาย</h1>
    </div>
    <div class="order-details table-responsive">
        <?php
        $sql = "SELECT id, orderID, cus_name, book_Name, orderQty, orderPrice, provinces 
                FROM history ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped table-hover">';
            echo '<thead class="table-dark"><tr><th>ลำดับ</th><th>เลขที่ออเดอร์</th><th>ชื่อผู้รับ</th><th>ชื่อหนังสือ</th><th>จำนวน</th><th>ยอดรวม</th><th>จังหวัด</th></tr></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['orderID']}</td>
                    <td>{$row['cus_name']}</td>
                    <td>{$row['book_Name']}</td>
                    <td>{$row['orderQty']}</td>
                    <td>".number_format($row['orderPrice'], 2)."</td>
                    <td>{$row['provinces']}</td>
                </tr>";
            }
            echo '</tbody></table>';

            // แสดง Pagination
            renderPagination($page, $total_pages);
        } else {
            echo "<div class='alert alert-info text-center'>ไม่พบข้อมูล</div>";
        }
        ?>
    </div>
</div>
</body>
</html>

<?php
function renderPagination($page, $total_pages) {
    if ($total_pages <= 1) return;
    echo '<nav><ul class="pagination justify-content-center">';
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