<?php
include 'condb.php';
session_start();

if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
}
// ดึงเฉพาะสถานะ 1 (รอตรวจสอบ)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$res_count = $conn->query("SELECT COUNT(*) as total FROM tb_order WHERE order_status = 1");
$total_pages = ceil($res_count->fetch_assoc()['total'] / $limit);

$sql = "SELECT * FROM tb_order WHERE order_status = 1 ORDER BY orderID DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสั่งซื้อใหม่</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 10px; }
        /* สไตล์เพิ่มเติมสำหรับระยะห่างปุ่ม */
        .management-btns {
            display: flex;
            gap: 15px; /* ปรับความห่างตรงนี้ (15px) */
            justify-content: center;
        }
    </style>
</head>
<body>
<?php include 'adminmenu.php'; ?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 text-primary"><i class="fas fa-clipboard-list"></i> รายการสั่งซื้อใหม่ (รอการตรวจสอบ)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">เลขที่ออเดอร์</th>
                            <th>ชื่อลูกค้า</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="text-center"><strong>#<?= $row["orderID"] ?></strong></td>
                                <td><?= htmlspecialchars($row["cus_name"]) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row["reg_date"])) ?></td>
                                <td class="text-center"><span class="badge bg-warning text-dark">รอตรวจสอบ</span></td>
                                <td>
                                    <div class="management-btns">
                                        <a href="admin_orderdetail.php?orderID=<?= $row["orderID"] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> รายละเอียด
                                        </a>
                                        <a href="approve_order.php?id=<?= $row["orderID"] ?>" class="btn btn-success btn-sm" onclick="return confirmAction('อนุมัติ', this)">
                                            <i class="fas fa-check"></i> อนุมัติ
                                        </a>
                                        <a href="reject_order.php?id=<?= $row["orderID"] ?>" class="btn btn-danger btn-sm" onclick="return confirmAction('ปฏิเสธ', this)">
                                            <i class="fas fa-times"></i> ปฏิเสธ
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } } else {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>ไม่มีรายการสั่งซื้อใหม่ในขณะนี้</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
            <?php renderPagination($page, $total_pages); ?>
        </div>
    </div>
</div>

<script>
// ฟังก์ชันช่วยยืนยันและป้องกันการกดซ้ำ
function confirmAction(type, btn) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะ "' + type + '" ออเดอร์นี้?\nการกระทำนี้ไม่สามารถย้อนกลับได้')) {
        // เมื่อกดยืนยันแล้ว ให้ปิดการคลิกที่ปุ่มเพื่อป้องกันการกดย้ำๆ
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.5';
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังดำเนินการ...';
        return true;
    }
    return false;
}
</script>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

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