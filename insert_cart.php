<?php
session_start();
include 'condb.php';

// 1. รับค่าจากฟอร์ม
$custName   = mysqli_real_escape_string($conn, $_POST['cus_name']);
$custTel    = mysqli_real_escape_string($conn, $_POST['cus_tel']);
$custAddr   = mysqli_real_escape_string($conn, $_POST['cus_add']);
$cus_id = isset($_POST['cus_id']) && is_numeric($_POST['cus_id']) ? (int)$_POST['cus_id'] : 0;
$sex        = $_POST['sex'] ?? '';    
$prov       = $_POST['Ref_prov_id'] ?? '';
$dist       = $_POST['Ref_dist_id'] ?? '';
$subdist    = $_POST['Ref_subdist_id'] ?? '';
$zip        = $_POST['zip_code'] ?? '';
$coupon_code = $_POST['coupon_code'] ?? '';

// รับค่าที่อยู่ละเอียด (ถ้ามี)
// 1. รับค่าจากฟอร์ม
$custName    = $_POST['cus_name'];
$custTel     = $_POST['cus_tel'];
$custAddr    = $_POST['cus_add'];
$coupon_code = $_POST['coupon_code'] ?? '';
$sex         = $_POST['sex'] ?? ''; // รับค่าเพศ

/// 2. คำนวณส่วนลดคูปอง
$coupon_price = 0;
if (!empty($coupon_code)) {
    $sql_cp = "SELECT discount FROM coupons WHERE coupon_code = '$coupon_code'";
    $res_cp = mysqli_query($conn, $sql_cp);
    if ($row_cp = mysqli_fetch_array($res_cp)) {
        $coupon_price = $row_cp['discount'];
    }
}

$total_price = ($_SESSION['sum_price'] ?? 0) - $coupon_price;
if($total_price < 0) $total_price = 0;

// 3. คำนวณยอดสุทธิ
$total_price = $_SESSION['sum_price'] - $coupon_price;
if($total_price < 0) $total_price = 0;

// 4. จัดการอัปโหลดรูปภาพสลิป
$new_file_name = "";
if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_file_name = "slip_" . time() . "." . $ext;
    copy($_FILES['image']['tmp_name'], "slip/" . $new_file_name);
}

// 5. บันทึกลง tb_order (จัดลำดับตามโครงสร้างตารางที่คุณให้มา)
$sql = "INSERT INTO tb_order (cus_id, cus_name, sex, provinces, amphures, districts, zip_code, address, telephone, total_price, order_status, image, coupon_price) 
        VALUES ('$cus_id', '$custName', '$sex', '$prov', '$dist', '$subdist', '$zip', '$custAddr', '$custTel', '$total_price', '1', '$new_file_name', '$coupon_price')";

if (mysqli_query($conn, $sql)) {
    $orderID = mysqli_insert_id($conn);
    // บันทึก order_detail และตัดสต็อก (ส่วนเดิมของคุณ)
    for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
        if (isset($_SESSION["strProductID"][$i]) && $_SESSION["strProductID"][$i] != "") {
            $p_id = $_SESSION["strProductID"][$i];
            $qty  = $_SESSION["strQty"][$i];
            $sql_p = "SELECT price FROM stocks WHERE book_id = '$p_id'";
            $res_p = mysqli_query($conn, $sql_p);
            $row_p = mysqli_fetch_array($res_p);
            $price = $row_p['price'];
            mysqli_query($conn, "INSERT INTO order_detail(orderID, book_id, orderPrice, orderQty) VALUES('$orderID', '$p_id', '$price', '$qty')");
            mysqli_query($conn, "UPDATE stocks SET amount = amount - $qty WHERE book_id = '$p_id'");
        }
    }
    unset($_SESSION["intLine"], $_SESSION["strProductID"], $_SESSION["strQty"], $_SESSION["sum_price"]);
    echo "<script>alert('สั่งซื้อเรียบร้อย'); window.location='print_order.php?orderID=$orderID';</script>";
}
?>