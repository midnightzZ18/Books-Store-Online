<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}

// เช็คและกำหนดค่าเริ่มต้นให้ตัวแปร Session ถ้ายังไม่มีการเลือกสินค้า
if (!isset($_SESSION["intLine"])) {
    $_SESSION["intLine"] = -1;
}

// ตรวจสอบส่วนลดและการยืนยันออเดอร์
$total_price = 0;
$discount = 0;

if (isset($_POST['confirm_order'])) {
    if (!empty($_POST['coupon_code'])) {
        $coupon_code = $_POST['coupon_code'];
        $sql_coupon = "SELECT discount FROM coupons WHERE coupon_code = ?";
        $stmt_coupon = $conn->prepare($sql_coupon);
        $stmt_coupon->bind_param("s", $coupon_code);
        $stmt_coupon->execute();
        $result_coupon = $stmt_coupon->get_result();
        if ($row_coupon = $result_coupon->fetch_assoc()) {
            $discount = $row_coupon['discount'];
        }
        $total_price = (isset($_SESSION['sum_price']) ? $_SESSION['sum_price'] : 0) - $discount;
    } else {
        $total_price = (isset($_SESSION['sum_price']) ? $_SESSION['sum_price'] : 0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="pb-5">
    <?php include 'menu.php';?>
    <br><br>
    <div class="container">
        <form id="form1" method="POST" action="insert_cart.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-hover">
                        <thead>
                            <tr> 
                                <th> ลำดับที่ </th>
                                <th> รูปภาพ </th>
                                <th> ชื่อสินค้า </th>
                                <th> ราคา </th>
                                <th> จำนวน </th>
                                <th> ราคารวม </th>
                                <th> เพิ่ม - ลด </th>
                                <th> ลบ </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sumPrice = 0;
                        $m = 1;
                        
                        // ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
                        if ($_SESSION["intLine"] > -1) {
                            for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
                                // ตรวจสอบว่ามี Product ID ในตำแหน่งนั้นๆ หรือไม่
                                if (isset($_SESSION["strProductID"][$i]) && $_SESSION["strProductID"][$i] != "") {
                                    $sql1 = "SELECT * FROM stocks WHERE book_id = '" . mysqli_real_escape_string($conn, $_SESSION["strProductID"][$i]) . "'";
                                    $result1 = mysqli_query($conn, $sql1);
                                    $row_book = mysqli_fetch_array($result1);

                                    if ($row_book) {
                                        $qty = $_SESSION["strQty"][$i];
                                        $price = $row_book['price'];
                                        $sum = $qty * $price;
                                        $sumPrice += $sum;
                        ?>
                                <tr>
                                    <td><?= $m ?></td>
                                    <td><img src="img/<?= $row_book['image'] ?>" width="80px" height="100" class="border"> </td>
                                    <td><?= $row_book['book_name'] ?></td>
                                    <td><?= number_format($price, 2) ?> </td>
                                    <td><?= $qty ?></td>
                                    <td><?= number_format($sum, 2) ?></td>
                                    <td>
                                        <a href="order.php?id=<?= $row_book['book_id'] ?>" class="btn btn-outline-info">+</a>
                                        <?php if ($qty > 1) { ?>
                                            <a href="order_del.php?id=<?= $row_book['book_id'] ?>" class="btn btn-outline-info">-</a>
                                        <?php } ?>
                                    </td>
                                    <td><a href="book_delete.php?Line=<?= $i ?>"><img src="img/delete.jpg" width="35px"></a></td>
                                </tr>
                        <?php
                                        $m++;
                                    }
                                }
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>ไม่มีสินค้าในตะกร้า</td></tr>";
                        }
                        $_SESSION["sum_price"] = $sumPrice;
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-end" colspan="5"><strong>ยอดรวมก่อนใช้คูปอง</strong></td>
                                <td class="text-end" id="originalTotalPrice"><strong><?= number_format($sumPrice, 2) ?></strong> บาท</td>
                            </tr>
                            <tr id="couponDiscountRow" style="display:none;">
                                <td class="text-end" colspan="5"><strong>ส่วนลดคูปอง</strong></td>
                                <td class="text-end text-danger" id="couponDiscountAmount"><strong>0.00</strong> บาท</td>
                            </tr>
                            <tr id="couponFinalRow" style="display:none;">
                                <td class="text-end" colspan="5"><strong>ยอดหลังใช้คูปอง</strong></td>
                                <td class="text-end" id="couponFinalPrice"><strong><?= number_format($sumPrice, 2) ?></strong> บาท</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div style="text-align:right">
                        <a href="shop.php" class="btn btn-outline-secondary">เลือกสินค้าเพิ่มเติม</a>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmOrderModal" <?= ($sumPrice == 0) ? 'disabled' : '' ?>>ยืนยันการสั่งซื้อ</button>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <h4>ข้อมูลสำหรับจัดส่งสินค้า</h4>
                    </div>
                    <?php
                        $user_name = $_SESSION['user_name'];
                        $sql = "SELECT uf.*, p.name_th AS province_name, a.name_th AS amphur_name, d.name_th AS district_name
                                FROM user_form uf
                                LEFT JOIN provinces p ON uf.Ref_prov_id = p.id
                                LEFT JOIN amphures a ON uf.Ref_dist_id = a.id
                                LEFT JOIN districts d ON uf.Ref_subdist_id = d.id
                                WHERE uf.user_name = '$user_name'";
                        $result = mysqli_query($conn, $sql);
                        if ($row = mysqli_fetch_array($result)) {
                    ?>  
                        <input type="hidden" name="cus_id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                            <label>ชื่อ - นามสกุล:</label>
                            <input type="text" name="cus_name" class="form-control" required value="<?= htmlspecialchars($row['name']) ?>">
                        </div>
                        <div class="mb-3">
                            <label>เบอร์โทรศัพท์:</label>
                            <input type="tel" name="cus_tel" class="form-control" required value="<?= htmlspecialchars($row['phone_number']) ?>">
                        </div>
                        <div class="mb-3">
                            <label>ที่อยู่:</label>
                            <textarea name="cus_add" class="form-control" required rows="3"><?= htmlspecialchars($row['address']) ?> <?= htmlspecialchars($row['district_name']) ?> <?= htmlspecialchars($row['amphur_name']) ?> <?= htmlspecialchars($row['province_name']) ?> <?= htmlspecialchars($row['zip_code']) ?></textarea>
                            <input type="hidden" name="Ref_prov_id" value="<?= $row['Ref_prov_id'] ?>">
                            <input type="hidden" name="Ref_dist_id" value="<?= $row['Ref_dist_id'] ?>">
                            <input type="hidden" name="Ref_subdist_id" value="<?= $row['Ref_subdist_id'] ?>">
                            <input type="hidden" name="zip_code" value="<?= $row['zip_code'] ?>">
                            <input type="hidden" name="sex" value="<?= $row['sex'] ?>">
                        </div>
                    <?php } ?>

                    <div class="mt-4">
                        <h4>เลือกคูปอง</h4>
                        <div class="input-group">
                            <select class="form-control" id="coupon" name="coupon_code">
                                <option value="">-- เลือกคูปอง --</option>
                                <?php
                                    $sql_coupons = "SELECT * FROM coupons";
                                    $result_coupons = mysqli_query($conn, $sql_coupons);
                                    while ($cp = mysqli_fetch_assoc($result_coupons)) {
                                        echo "<option value='" . $cp['coupon_code'] . "'>" . $cp['coupon_code'] . " (ลด " . $cp['discount'] . " บาท)</option>";
                                    }
                                ?>
                            </select>
                            <button type="button" class="btn btn-info" onclick="applyCouponJS()">คำนวณส่วนลด</button>
                        </div>
                        <div id="discountInfo" class="mt-2 text-success"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmOrderModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">ยืนยันการสั่งซื้อ</h5>
                            <button type="button" class="btn-close" data-bs-modal="dismiss"></button>
                        </div>
                        <div class="modal-body">
                            <p>ยอดชำระสุทธิ: <span id="discountedPriceModal"><?= number_format($sumPrice, 2) ?></span> บาท</p>
                            <div class="mb-3">
                                <label>แนบหลักฐานการโอนเงิน:</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <p class="text-danger">** โอนเงินเข้าบัญชี: 020246929143 (ออมสิน) **</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary" name="confirm_order">ยืนยันและส่งคำสั่งซื้อ</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function applyCouponJS() {
            var selectedCoupon = document.getElementById('coupon').value;
            var totalPrice = <?= json_encode($sumPrice) ?>;
            var discount = 0;

            // ดึงข้อมูลส่วนลดจาก JS (ค่าถูก Generate จาก PHP)
            <?php
            mysqli_data_seek($result_coupons, 0);
            while ($cp = mysqli_fetch_assoc($result_coupons)) {
                echo "if (selectedCoupon === '" . $cp['coupon_code'] . "') { discount = " . $cp['discount'] . "; }";
            }
            ?>

            var finalPrice = totalPrice - discount;
            if (finalPrice < 0) finalPrice = 0;

            document.getElementById('originalTotalPrice').innerHTML = "<strong>" + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + "</strong> บาท";
            document.getElementById('couponDiscountAmount').innerHTML = "<strong>" + discount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + "</strong> บาท";
            document.getElementById('couponFinalPrice').innerHTML = "<strong>" + finalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + "</strong> บาท";
            document.getElementById('discountedPriceModal').innerText = finalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            if (discount > 0) {
                document.getElementById('couponDiscountRow').style.display = '';
                document.getElementById('couponFinalRow').style.display = '';
                document.getElementById('discountInfo').innerHTML = "<strong>ยอดเต็ม " + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + " บาท</strong>, ลด " + discount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + " บาท, ยอดสุทธิ " + finalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + " บาท";
            } else if (selectedCoupon === "") {
                document.getElementById('couponDiscountRow').style.display = 'none';
                document.getElementById('couponFinalRow').style.display = 'none';
                document.getElementById('discountInfo').innerText = "ยังไม่ได้เลือกคูปอง";
            } else {
                document.getElementById('couponDiscountRow').style.display = 'none';
                document.getElementById('couponFinalRow').style.display = 'none';
                document.getElementById('discountInfo').innerText = "คูปองไม่สามารถใช้งานได้";
            }

            alert('คำนวณส่วนลดเรียบร้อยแล้ว\nยอดสุทธิ: ' + finalPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' บาท');
        }
    </script>
</body>
</html>