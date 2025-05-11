<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}

// Check if the user has clicked the confirm order button
if (isset($_POST['confirm_order'])) {
    // Check if the coupon code is selected
    if (!empty($_POST['coupon_code'])) {
        // Handle order with coupon
        $coupon_code = $_POST['coupon_code'];
        $discount = 0;
        // Retrieve discount amount from the database based on the selected coupon code
        $sql_coupon = "SELECT discount FROM coupons WHERE coupon_code = ?";
        $stmt_coupon = $conn->prepare($sql_coupon);
        $stmt_coupon->bind_param("s", $coupon_code);
        $stmt_coupon->execute();
        $result_coupon = $stmt_coupon->get_result();
        if ($row_coupon = $result_coupon->fetch_assoc()) {
            $discount = $row_coupon['discount'];
        }
        // Calculate total price after applying discount
        $total_price = $_SESSION['sum_price'] - $discount;
        echo "Order with coupon code: $coupon_code, Discount: $discount บาท, Total Price: $total_price บาท";
    } else {
        // Handle order without coupon
        $total_price = $_SESSION['sum_price'];
        echo "Order without coupon, Total Price: $total_price บาท";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'menu.php';?>
    <br><br>
    <div class="container">
        <form id="form1" method="POST" action="insert_cart.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-hover">
                        <tr> 
                            <th> ลำดับที่ </th>
                            <th></th>
                            <th> ชื่อสินค้า </th>
                            <th> ราคา </th>
                            <th> จำนวน </th>
                            <th> ราคารวม </th>
                            <th> เพิ่ม - ลด </th>
                            <th> ลบ </th>
                        </tr>
                        
                        <?php
                        $Total = 0;
                        $sumPrice = 0;
                        $m = 1;
                        for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
                            if (($_SESSION["strProductID"][$i]) != "") {
                                $sql1 = "SELECT * FROM stocks WHERE book_id = '" . $_SESSION["strProductID"][$i] . "'";
                                $result1 = mysqli_query($conn, $sql1);
                                $row_book = mysqli_fetch_array($result1);

                                $_SESSION["price"] = $row_book['price'];
                                $Total = $_SESSION["strQty"][$i];
                                $sum = $Total * $row_book['price'];
                                $sumPrice = $sumPrice + $sum;
                                $_SESSION["sum_price"] = $sumPrice;
// Check if the user has clicked the confirm order button
if (isset($_POST['confirm_order'])) {
    // Check if the coupon code is selected
    if (!empty($_POST['coupon_code'])) {
        // Handle order with coupon
        // โค้ดเดิมตามที่ได้แก้ไขด้านบน
    } else {
        // Handle order without coupon
        // โค้ดเดิมตามที่ได้แก้ไขด้านบน
    }
} elseif (isset($_POST['total_price'])) {
    // รับค่า $total_price ที่ส่งกลับมาจากการคำนวณใน JavaScript
    $total_price = $_POST['total_price'];
} else {
    // ค่าเริ่มต้นสำหรับ $total_price หากไม่มีการส่งค่ามา
    $total_price = $sumPrice;
}

                        ?>
                                <tr>
                                    <td><?= $m ?></td>
                                    <td><img src="img/<?= $row_book['image'] ?>" width="80px" height="100" class="border"> </td>
                                    <td><?= $row_book['book_name'] ?></td>
                                    <td><?= $row_book['price'] ?> </td>
                                    <td><?= $_SESSION["strQty"][$i] ?></td>
                                    <td><?= $sum ?></td>
                                    <td>
                                        <a href="order.php?id=<?= $row_book['book_id'] ?>" class="btn btn-outline-info">+</a>
                                        <?php if ($_SESSION["strQty"][$i] > 1) { ?>
                                            <a href="order_del.php?id=<?= $row_book['book_id'] ?>" class="btn btn-outline-info">-</a>
                                        <?php } ?>
                                    </td>
                                    <td><a href="book_delete.php?Line=<?= $i ?>"><img src="img/delete.jpg" width="35px"></a></td>
                                </tr>
                        <?php
                                $m = $m + 1;
                            }
                        }
                        ?>
                        <tr>
                            <td class="text-end" colspan="5">รวมเป็นเงิน</td>
                            <td class="text-end" id="totalPrice"><?= $sumPrice ?> บาท</td>
                        </tr>
                        

                    </table>
                    <!-- Buttons for selecting products and confirming order -->
                    <div style="text-align:right" action="update_coupon.php">
                        <a href="shop.php"><button type="button" class="btn btn-outline-secondary">เลือกสินค้าเพิ่มเติม</button></a>
                        <!-- Button to trigger the confirmation modal -->
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#confirmOrderModal">ยืนยันการสั่งซื้อ</button>
                    </div>
                </div>
                <br><br>
               <!-- Modal for confirming the order -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmOrderModalLabel">Confirm Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the calculated total price after applying discount -->
                <p>Total Price: <span id="discountedPriceModal"><?= $total_price ?> บาท</span></p>
                Are you sure you want to place the order?<br>
                <b> *** อย่าลืมชำระเงินด้วยนะครับ <br>
       เลขบัญชี 020246929143  ออมสิน  <br>
           ชื่อบัญชี นายรักษ์วงศ์กฎ วงศ์วิเศษ

            </div>
            <div class="modal-footer">
                <!-- Button to cancel the order -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Submit button to confirm the order -->
                <button type="submit" class="btn btn-primary" name="confirm_order">Confirm Order</button>
            </div>
                            <form action="insert_cart.php" method="post" enctype="multipart/form-data">
    <!-- ฟิลด์อื่นๆ ที่มีอยู่ -->
    <div class="mb-3">
        <label for="image">อัปโหลดรูปภาพ:</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <!-- ปุ่มยืนยัน -->
                        </div>
                    </div>
                </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success" role="alert">
                            <h4>ข้อมูลสำหรับจัดส่งสินค้า</h4>
                        </div><br>
                        <?php
                            $user_name = $_SESSION['user_name'];
                            $sql = "SELECT uf.name, uf.address, uf.phone_number, uf.id, p.name_th AS province_name, a.name_th AS amphur_name, d.name_th AS district_name, uf.zip_code
                                    FROM user_form uf
                                    JOIN provinces p ON uf.Ref_prov_id = p.id
                                    JOIN amphures a ON uf.Ref_dist_id = a.id
                                    JOIN districts d ON uf.Ref_subdist_id = d.id
                                    WHERE uf.user_name = '$user_name'";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_array($result)) {
                        ?>  
                            <div>
                                <label for="cus_id">ID ลูกค้า:</label>
                                <input name="cus_id" id="cus_id" class="form-control" value="<?= htmlspecialchars($row['id']) ?>"><br>
                            </div>
                            <div>
                                <label for="cus_name">ชื่อ - นามสกุล:</label>
                                <input type="text" name="cus_name" id="cus_name" class="form-control" required placeholder="กรอกชื่อ - นามสกุล" value="<?= htmlspecialchars($row['name']) ?>"><br>
                            </div>
                            <div>
                                <label for="cus_tel">เบอร์โทรศัพท์:</label>
                                <input type="tel" name="cus_tel" id="cus_tel" class="form-control" required placeholder="กรอกเบอร์โทรศัพท์" value="<?= htmlspecialchars($row['phone_number']) ?>"><br>
                            </div>
                            
                            <div>
                                <label for="cus_add">รายละเอียดที่อยู่จัดส่งสินค้า:</label>
                                <textarea name="cus_add" id="cus_add" class="form-control" required placeholder="บ้านเลขที่ หมู่บ้าน ตำบล..." rows="3"><?= htmlspecialchars($row['address']) ?></textarea><br>
                            </div>
                            <div>
                                <label for="cus_prov">จังหวัด:</label>
                                <input type="text" name="cus_prov" id="cus_prov" class="form-control" value="<?= htmlspecialchars($row['province_name']) ?>"><br>
                            </div>
                            <div>
                                <label for="cus_amp">อำเภอ:</label>
                                <input type="text" class="form-control" name="cus_amp" id="cus_amp" value="<?= htmlspecialchars($row['amphur_name']) ?>"><br>
                            </div>
                            <div>
                                <label for="cus_dist">ตำบล:</label>
                                <input type="text" class="form-control" name="cus_dist" id="cus_dist" value="<?= htmlspecialchars($row['district_name']) ?>"><br>
                            </div>
                            <div>
                                <label for="cus_zip">รหัสไปรษณีย์:</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control" value="<?= htmlspecialchars($row['zip_code']) ?>"><br>
                            </div>
                        <?php } ?>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>เลือกคูปอง</h4>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="coupon">คูปอง:</label>
                                        <select class="form-control" id="coupon" name="coupon_code">
                                            <option value="">-- เลือกคูปอง --</option>
                                            <?php
                                                $sql_coupons = "SELECT * FROM coupons";
                                                $result_coupons = mysqli_query($conn, $sql_coupons);
                                                while ($row_coupon = mysqli_fetch_assoc($result_coupons)) {
                                                    echo "<option value='" . $row_coupon['coupon_code'] . "'>" . $row_coupon['coupon_code'] . " (ส่วนลด " . $row_coupon['discount'] . " บาท)</option>";
                                                }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-info" onclick="applyCoupon()">คำนวณคูปอง</button>

                                    </div>
                                </form><br><br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        function applyCoupon() {
            var selectedCoupon = document.getElementById('coupon').value;
            if(selectedCoupon !== '') {
                document.getElementById('form1').submit();
            } else {
                alert('กรุณาเลือกคูปอง');
            }
        }
    </script>
     <script>
        function applyCoupon() {
            var selectedCoupon = document.getElementById('coupon').value;
            if (selectedCoupon !== '') {
                alert('คำนวณคูปองเรียบร้อย');
                // รับค่าราคาสินค้ารวมจาก PHP
                var totalPrice = <?= $sumPrice ?>;
                // ส่งค่าราคาสินค้ารวมไปยังฟังก์ชันอัปเดตค่า
                updateTotalPrice(totalPrice);
            } else {
                alert('กรุณาเลือกคูปอง');
            }
        }

        function updateTotalPrice(totalPrice) {
            // คำนวณส่วนลดจากคูปองและอัปเดตราคาสินค้าที่แสดง
            var selectedCoupon = document.getElementById('coupon').value;
            var discount = 0;
            <?php
            $sql_coupons = "SELECT * FROM coupons";
            $result_coupons = mysqli_query($conn, $sql_coupons);
            while ($row_coupon = mysqli_fetch_assoc($result_coupons)) {
                echo "if (selectedCoupon === '" . $row_coupon['coupon_code'] . "') {";
                echo "discount = " . $row_coupon['discount'] . ";";
                echo "}";
            }
            ?>
            var discountedPrice = totalPrice - discount;
            document.getElementById('totalPrice').innerText = discountedPrice + " บาท";
        }

        function updateTotalPrice(totalPrice) {
            // คำนวณส่วนลดจากคูปองและอัปเดตราคาสินค้าที่แสดง
            var selectedCoupon = document.getElementById('coupon').value;
            var discount = 0;
            <?php
            $sql_coupons = "SELECT * FROM coupons";
            $result_coupons = mysqli_query($conn, $sql_coupons);
            while ($row_coupon = mysqli_fetch_assoc($result_coupons)) {
                echo "if (selectedCoupon === '" . $row_coupon['coupon_code'] . "') {";
                echo "discount = " . $row_coupon['discount'] . ";";
                echo "}";
            }
            ?>
            var discountedPrice = totalPrice - discount;
            document.getElementById('totalPrice').innerText = discountedPrice + " บาท";
            document.getElementById('discountedPriceModal').innerText = discountedPrice + " บาท";
        }
    </script>



</body>
</html>
