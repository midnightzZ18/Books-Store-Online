<?php
include 'condb.php';
session_start();

/* =========================
   ตรวจสอบการ Login
========================= */
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}

$user_name = $_SESSION['user_name'];

/* =========================
   ดึงข้อมูลผู้ใช้
========================= */
$sql_user = "SELECT 
                uf.*,
                p.name_th AS province_name,
                a.name_th AS amphur_name,
                d.name_th AS district_name
            FROM user_form uf
            LEFT JOIN provinces p ON uf.Ref_prov_id = p.id
            LEFT JOIN amphures a ON uf.Ref_dist_id = a.id
            LEFT JOIN districts d ON uf.Ref_subdist_id = d.id
            WHERE uf.user_name = '$user_name'";

$res_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($res_user);

if (!$user) {
    echo "ไม่พบข้อมูลผู้ใช้งาน";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ข้อมูลส่วนตัว - Online Book Store</title>

<link rel="icon" type="image/png" href="img/logo-web.png">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

<style>
body{
    background:#f8f9fa;
    font-family:'Kanit',sans-serif;
}

.profile-card{
    background:#fff;
    border:none;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

.order-card{
    background:#fff;
    border:none;
    border-left:5px solid #ad1457;
    border-radius:12px;
    margin-bottom:15px;
    transition:.3s;
}

.order-card:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.status-badge{
    padding:6px 14px;
    border-radius:30px;
    font-size:.85rem;
    font-weight:bold;
}

.text-pink{
    color:#ad1457 !important;
}
</style>
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container py-5">
<div class="row">

<!-- =========================
     โปรไฟล์
========================= -->
<div class="col-lg-4 mb-4">

<div class="card profile-card p-4">

<div class="text-center mb-3">
    <img src="img/account.png" width="80" class="mb-2">
    <h4 class="fw-bold"><?= htmlspecialchars($user['name']) ?></h4>
    <span class="badge bg-info text-dark">สมาชิกทั่วไป</span>
</div>

<hr>

<p>
<strong><i class="fas fa-envelope me-2 text-pink"></i>อีเมล:</strong>
<?= htmlspecialchars($user['email']) ?>
</p>

<p>
<strong><i class="fas fa-phone me-2 text-pink"></i>เบอร์โทร:</strong>
<?= htmlspecialchars($user['phone_number']) ?>
</p>

<p>
<strong><i class="fas fa-map-marker-alt me-2 text-pink"></i>ที่อยู่จัดส่ง:</strong><br>

<?= htmlspecialchars($user['address']) ?><br>

ต.<?= htmlspecialchars($user['district_name']) ?>
อ.<?= htmlspecialchars($user['amphur_name']) ?><br>

จ.<?= htmlspecialchars($user['province_name']) ?>
<?= htmlspecialchars($user['zip_code']) ?>
</p>

</div>
</div>

<!-- =========================
     ประวัติการสั่งซื้อ
========================= -->
<div class="col-lg-8">

<div class="card profile-card p-4">

<h4 class="mb-4 fw-bold">
<i class="fas fa-history me-2 text-pink"></i>
ประวัติการสั่งซื้อ
</h4>

<?php
$sql_orders = "SELECT *
               FROM tb_order
               WHERE cus_id = '{$user['id']}'
               ORDER BY reg_date DESC";

$res_orders = mysqli_query($conn, $sql_orders);

if ($res_orders && mysqli_num_rows($res_orders) > 0):

while ($order = mysqli_fetch_assoc($res_orders)):

$orderID = $order['orderID'];
$status = $order['order_status'];
?>

<div class="card order-card p-3">

<div class="d-flex justify-content-between align-items-center mb-2">

<div>
<span class="fw-bold text-primary">
ออเดอร์ #<?= $orderID ?>
</span>

<small class="text-muted ms-2">
<?= date('d/m/Y H:i', strtotime($order['reg_date'])) ?>
</small>
</div>

<div>
<?php
/* =========================
   เหลือแค่ 2 สถานะ
   1 = รอยืนยันการชำระเงิน
   2 ขึ้นไป = สั่งซื้อสำเร็จ
========================= */
if ($status == 1) {
    echo '<span class="status-badge bg-warning text-dark">รอยืนยันการชำระเงิน</span>';
} else {
    echo '<span class="status-badge bg-success text-white">สั่งซื้อสำเร็จ</span>';
}
?>
</div>

</div>

<!-- รายการสินค้า -->
<div class="small text-muted mb-2 py-2 border-top border-bottom">

<strong>สินค้า:</strong>

<?php
$sql_items = "SELECT od.orderQty, s.book_name
              FROM order_detail od
              JOIN stocks s ON od.book_id = s.book_id
              WHERE od.orderID = '$orderID'";

$res_items = mysqli_query($conn, $sql_items);

$item_list = [];

while ($item = mysqli_fetch_assoc($res_items)) {
    $item_list[] = htmlspecialchars($item['book_name']) . " (x" . $item['orderQty'] . ")";
}

echo implode(", ", $item_list);
?>

</div>

<div class="d-flex justify-content-between align-items-center">

<div>
<?php if ($status == 1): ?>
<small class="text-muted">
รอทางร้านตรวจสอบสลิปการโอนเงิน
</small>
<?php else: ?>
<small class="text-success fw-bold">
<i class="fas fa-check-circle me-1"></i>
ชำระเงินเรียบร้อยแล้ว
</small>
<?php endif; ?>
</div>

<div class="text-end">
<span class="text-muted small">ยอดชำระสุทธิ:</span>
<span class="h5 mb-0 text-danger fw-bold">
฿<?= number_format($order['total_price'], 2) ?>
</span>
</div>

</div>

</div>

<?php
endwhile;

else:
?>

<div class="text-center py-5 text-muted">
ยังไม่มีประวัติการสั่งซื้อ
</div>

<?php endif; ?>

</div>
</div>

</div>
</div>

</body>
</html>