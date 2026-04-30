<?php
    include 'condb.php';
    session_start();

    // เช็ค Login Admin
    if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
    }

    // --- ส่วนประมวลผล Logic ---
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // 1. เพิ่มคูปองใหม่
        if (isset($_POST['add_coupon'])) {
            $coupon_code = mysqli_real_escape_string($conn, $_POST['coupon_code']);
            $discount = intval($_POST['discount']);
            $status = intval($_POST['status']);

            $sql = "INSERT INTO coupons (coupon_code, discount, status) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sii', $coupon_code, $discount, $status);
            mysqli_stmt_execute($stmt);
            header("location:addcoupon.php");
        } 
        
        // 2. สลับสถานะ (เปิด/ปิด) คูปอง <--- ส่วนที่เพิ่มใหม่
        elseif (isset($_POST['toggle_status'])) {
            $coupon_id = intval($_POST['coupon_id']);
            $current_status = intval($_POST['current_status']);
            $new_status = ($current_status == 1) ? 0 : 1; // ถ้า 1 ให้เป็น 0, ถ้า 0 ให้เป็น 1

            $sql = "UPDATE coupons SET status = ? WHERE coupon_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ii', $new_status, $coupon_id);
            mysqli_stmt_execute($stmt);
            header("location:addcoupon.php");
        }

        // 3. ลบคูปอง
        elseif (isset($_POST['delete_coupon'])) {
            $coupon_id = intval($_POST['coupon_id']);
            $sql = "DELETE FROM coupons WHERE coupon_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $coupon_id);
            mysqli_stmt_execute($stmt);
            header("location:addcoupon.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>จัดการคูปอง - Admin</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .status-active { color: #28a745; font-weight: bold; }
        .status-inactive { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<?php include 'adminmenu.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">เพิ่มคูปอง</h5>
                <form method="POST">
                    <input type="text" name="coupon_code" class="form-control mb-2" placeholder="รหัสคูปอง" required>
                    <input type="number" name="discount" class="form-control mb-2" placeholder="ส่วนลด (บาท)" required>
                    <select name="status" class="form-control mb-3">
                        <option value="1">เปิดใช้งานทันที</option>
                        <option value="0">ปิดไว้ก่อน</option>
                    </select>
                    <button type="submit" name="add_coupon" class="btn btn-primary w-100">บันทึก</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">คูปองทั้งหมด</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ชื่อคูปอง</th>
                            <th>ส่วนลด</th>
                            <th>สถานะ</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM coupons ORDER BY coupon_id DESC");
                        while($row = mysqli_fetch_assoc($result)) {
                            $is_active = ($row['status'] == 1);
                        ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= $row['coupon_code'] ?></td>
                            <td>฿<?= number_format($row['discount']) ?></td>
                            <td>
                                <span class="<?= $is_active ? 'status-active' : 'status-inactive' ?>">
                                    <?= $is_active ? '<i class="fas fa-check-circle"></i> เปิดใช้งาน' : '<i class="fas fa-times-circle"></i> ปิดใช้งาน' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form method="POST">
                                        <input type="hidden" name="coupon_id" value="<?= $row['coupon_id'] ?>">
                                        <input type="hidden" name="current_status" value="<?= $row['status'] ?>">
                                        <button type="submit" name="toggle_status" class="btn btn-sm <?= $is_active ? 'btn-outline-secondary' : 'btn-outline-success' ?>">
                                            <?= $is_active ? 'ปิดการใช้' : 'เปิดการใช้' ?>
                                        </button>
                                    </form>

                                    <form method="POST" onsubmit="return confirm('ลบคูปองนี้ใช่ไหม?')">
                                        <input type="hidden" name="coupon_id" value="<?= $row['coupon_id'] ?>">
                                        <button type="submit" name="delete_coupon" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>