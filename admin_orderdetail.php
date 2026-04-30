<?php
include 'condb.php';
session_start();

// 1. ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
    header('location:login_form.php');
    exit;
}

$order = null;
$orderID = 0;

// 2. ดึงข้อมูลคำสั่งซื้อหลัก
if (isset($_GET['orderID'])) {
    $orderID = intval($_GET['orderID']);

    $stmt = $conn->prepare("
        SELECT t.*, 
               p.name_th AS province_name, 
               a.name_th AS amphur_name, 
               d.name_th AS district_name
        FROM tb_order t
        LEFT JOIN provinces p ON t.provinces = p.id
        LEFT JOIN amphures a ON t.amphures = a.id
        LEFT JOIN districts d ON t.districts = d.id
        WHERE t.orderID = ?
    ");

    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); border-radius: 12px; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .img-slip-preview { max-width: 100%; height: auto; border-radius: 8px; cursor: pointer; transition: .3s; }
        .img-slip-preview:hover { opacity: .8; transform: scale(1.02); }
    </style>
</head>
<body>

<?php include 'adminmenu.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-file-invoice text-primary"></i> รายละเอียดคำสั่งซื้อ</h2>
        <a href="confirm_order.php" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> กลับ
        </a>
    </div>

    <?php if ($order): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 p-1">ข้อมูลลูกค้า #<?= $orderID ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <p class="mb-1 text-muted small uppercase">ชื่อผู้รับ</p>
                            <h5 class="fw-bold"><?= htmlspecialchars($order['cus_name']) ?></h5>
                            <p class="mb-3"><i class="fas fa-phone-alt me-2"></i><?= htmlspecialchars($order['telephone']) ?></p>
                            
                            <p class="mb-1 text-muted small uppercase">ที่อยู่จัดส่ง</p>
                            <div class="p-3 bg-light rounded border">
                                <?= htmlspecialchars($order['address']) ?><br>
                                <strong>ตำบล:</strong> <?= htmlspecialchars($order['district_name'] ?: '-') ?><br>
                                <strong>อำเภอ:</strong> <?= htmlspecialchars($order['amphur_name'] ?: '-') ?><br>
                                <strong>จังหวัด:</strong> <?= htmlspecialchars($order['province_name'] ?: '-') ?><br>
                                <strong>รหัสไปรษณีย์:</strong> <?= htmlspecialchars($order['zip_code'] ?: '-') ?>
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <p class="mb-1 text-muted small">วันที่สั่งซื้อ</p>
                            <p class="fw-bold"><i class="far fa-calendar-alt me-2"></i><?= date('d/m/Y H:i', strtotime($order['reg_date'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 p-1">รายการสินค้า</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">สินค้า</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end pe-4">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal = 0;
                            $detail_stmt = $conn->prepare("
                                SELECT od.orderPrice, od.orderQty, s.book_name
                                FROM order_detail od
                                JOIN stocks s ON od.book_id = s.book_id
                                WHERE od.orderID = ?
                            ");
                            $detail_stmt->bind_param("i", $orderID);
                            $detail_stmt->execute();
                            $detail_result = $detail_stmt->get_result();

                            while ($item = $detail_result->fetch_assoc()) :
                                $line_total = $item['orderPrice'] * $item['orderQty'];
                                $subtotal += $line_total;
                            ?>
                            <tr>
                                <td class="ps-4"><?= htmlspecialchars($item['book_name']) ?></td>
                                <td class="text-center">฿<?= number_format($item['orderPrice'], 2) ?></td>
                                <td class="text-center"><?= $item['orderQty'] ?></td>
                                <td class="text-end pe-4">฿<?= number_format($line_total, 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end">รวมราคาสินค้า:</td>
                                <td class="text-end pe-4">฿<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end text-danger">ส่วนลดคูปอง:</td>
                                <td class="text-end text-danger pe-4">-฿<?= number_format($order['coupon_price'], 2) ?></td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <td colspan="3" class="text-end">ยอดชำระสุทธิ:</td>
                                <td class="text-end pe-4">฿<?= number_format($order['total_price'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 p-1 fw-bold">จัดการออเดอร์</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-success btn-lg w-100 mb-3 shadow-sm" onclick="approveOrder(<?= $orderID ?>)">
                        <i class="fas fa-check-circle me-2"></i>อนุมัติออเดอร์
                    </button>
                    <button class="btn btn-danger w-100 shadow-sm" onclick="rejectOrder(<?= $orderID ?>)">
                        <i class="fas fa-times-circle me-2"></i>ปฏิเสธรายการ
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 p-1">หลักฐานการโอนเงิน</h5>
                </div>
                <div class="card-body text-center p-4">
                    <?php if (!empty($order['image'])): ?>
                        <img src="slip/<?= htmlspecialchars($order['image']) ?>" class="img-slip-preview border shadow-sm" data-bs-toggle="modal" data-bs-target="#imageModal">
                        <p class="mt-3 text-muted small"><i class="fas fa-search-plus me-1"></i> คลิกเพื่อขยายรูปภาพ</p>
                    <?php else: ?>
                        <div class="py-5 text-muted">
                            <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i><br>
                            ไม่มีแนบหลักฐาน
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบข้อมูลคำสั่งซื้อที่ระบุในระบบ
    </div>
    <?php endif; ?>
</div>

<?php if ($order && !empty($order['image'])): ?>
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">หลักฐานการชำระเงิน #<?= $orderID ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img src="slip/<?= htmlspecialchars($order['image']) ?>" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function approveOrder(id){
    if(confirm('คุณต้องการ "อนุมัติ" รายการสั่งซื้อนี้ใช่หรือไม่?')){
        window.location.href='approve_order.php?id='+id;
    }
}
function rejectOrder(id){
    if(confirm('คุณต้องการ "ปฏิเสธ" รายการสั่งซื้อนี้ใช่หรือไม่?')){
        window.location.href='reject_order.php?id='+id;
    }
}
</script>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>